/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.recommendation_manager;

import com.mymed.controller.core.manager.reputation.api.recommendation_manager.ReputationManager;
import com.mymed.controller.core.manager.reputation.api.recommendation_manager.IReputationAlgorithm;
import com.mymed.controller.core.manager.reputation.api.recommendation_manager.VerdictManager;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.MymedAppId;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.MymedUserId;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.IMymedRepId;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.ReputationRole;
import ch.qos.logback.classic.Level;
import com.mymed.utils.MLogger;
import com.mymed.model.data.reputation.MReputationBean;
import java.io.File;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.controller.core.manager.reputation.globals.Constants;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.db.facade.TransactionManager;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.MymedAppUserId;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;

import org.junit.After;
import org.junit.AfterClass;
import org.junit.Before;
import org.junit.BeforeClass;
import org.junit.Test;
import org.junit.Ignore;
import static org.junit.Assert.*;

/**
 *
 * @author peter
 */
public class VerdictManagerTest {

    final WrapperConfiguration conf;
    VerdictManager verdictManager;
    final String listenAddress;
    final int thriftPort;
    final CassandraWrapper wrapper;

    public VerdictManagerTest() throws InternalBackEndException {
        conf = new WrapperConfiguration(new File(Constants.CONFIGURATION_FILE_PATH));

        listenAddress = conf.getCassandraListenAddress();
        thriftPort = conf.getThriftPort();

        System.out.println("Connection information:");
        System.out.println("\tListen Address: " + listenAddress);
        System.out.println("\tThrift Port   : " + thriftPort);
        System.out.println("\n");
        MLogger.getLog().setLevel(Level.OFF);
        MLogger.getDebugLog().setLevel(Level.OFF);
        MLogger.getInfoLog().setLevel(Level.OFF);

        wrapper = new CassandraWrapper(listenAddress, thriftPort);

        System.out.println("Opening Cassandra connection...");


        wrapper.set_keyspace(Constants.KEYSPACE);

        IReputationAlgorithm alg = new AverageReputationAlgorithm(100);
        verdictManager = new VerdictManager(alg);
    }

    @BeforeClass
    public static void setUpClass() throws Exception {
    }

    @AfterClass
    public static void tearDownClass() throws Exception {
    }

    @Before
    public void setUp() throws InternalBackEndException {
        wrapper.truncate("Verdict");
        wrapper.truncate("VerdictsTimeOrdered");
        wrapper.truncate("ReputationEntity");
    }

    @After
    public void tearDown() {
    }

    @Test
    public void testUpdateReputation() throws InternalBackEndException {
        System.out.println("testUpdateReputation");
        verdictManager.setReputationAlgorithm(new AverageReputationAlgorithm(100));
        TransactionManager.getInstance().truncate("VerdictsTimeOrdered");
        String appId = "17";
        String user1 = "user1";
        String user2 = "user2";
        String user3 = "user3";
        // 2 performs service for 1
        //   1 rates 2 0.9, 2 rates 1 1.0
        IMymedRepId u1Cons = new MymedAppUserId(appId, user1, ReputationRole.Consumer);
        IMymedRepId u2Prod = new MymedAppUserId(appId, user2, ReputationRole.Producer);
        verdictManager.update(u1Cons, u2Prod, 0.9);
        verdictManager.update(u2Prod, u1Cons, 1.0);
        // 3 performs service for 1
        //   1 rates 3 0.4, 3 rates 1 0.5
        IMymedRepId u3Prod = new MymedAppUserId(appId, user3, ReputationRole.Producer);
        verdictManager.update(u1Cons, u3Prod, 0.4);
        verdictManager.update(u3Prod, u1Cons, 0.5);
        // 3 performs service for 2
        //   2 rates 3 0.7, 3 rates 2 0.4
        IMymedRepId u2Cons = new MymedAppUserId(appId, user2, ReputationRole.Consumer);
        verdictManager.update(u2Cons, u3Prod, 0.7);
        verdictManager.update(u3Prod, u2Cons, 0.4);

        // calculate reps
        //IReputationAlgorithm reputationAlgorithm = new AverageReputationAlgorithm(100);
        //ReputationUpdaterFactory reputationUpdaterFactory = new ReputationUpdaterFactory(reputationAlgorithm);

        ReputationManager reputationManager = new ReputationManager();
        // test reps
        //  user1 as producer, should be 0 ratings
        IMymedRepId repId = new MymedAppUserId(appId, user1, ReputationRole.Producer);
        MReputationBean rep = reputationManager.read(repId);
        assertEquals(0, rep.getNoOfRatings());
        //  user1 as consumer
        repId = new MymedAppUserId(appId, user1, ReputationRole.Consumer);
        rep = reputationManager.read(repId);
        assertEquals(2, rep.getNoOfRatings());
        assertEquals(0.75, rep.getReputation(), 0.01);
        //  user2 as producer
        repId = new MymedAppUserId(appId, user2, ReputationRole.Producer);
        rep = reputationManager.read(repId);
        assertEquals(1, rep.getNoOfRatings());
        assertEquals(0.9, rep.getReputation(), 0.01);
        //  user2 as consumer
        repId = new MymedAppUserId(appId, user2, ReputationRole.Consumer);
        rep = reputationManager.read(repId);
        assertEquals(1, rep.getNoOfRatings());
        assertEquals(0.4, rep.getReputation(), 0.01);
        //  user3 as producer
        repId = new MymedAppUserId(appId, user3, ReputationRole.Producer);
        rep = reputationManager.read(repId);
        assertEquals(2, rep.getNoOfRatings());
        assertEquals(0.55, rep.getReputation(), 0.01);
        //  user3 as consumer
        repId = new MymedAppUserId(appId, user3, ReputationRole.Consumer);
        rep = reputationManager.read(repId);
        assertEquals(0, rep.getNoOfRatings());
        // test the aggregates

        // AppUsers, all roles
        repId = new MymedAppUserId(appId, user1);  // AppUser 1
        rep = reputationManager.read(repId);
        assertEquals(2, rep.getNoOfRatings());
        assertEquals(0.75, rep.getReputation(), 0.01);
        repId = new MymedAppUserId(appId, user2);  // AppUser 2
        rep = reputationManager.read(repId);
        assertEquals(2, rep.getNoOfRatings());
        assertEquals(0.65, rep.getReputation(), 0.01);
        repId = new MymedAppUserId(appId, user3);  // AppUser 3
        rep = reputationManager.read(repId);
        assertEquals(2, rep.getNoOfRatings());
        assertEquals(0.55, rep.getReputation(), 0.01);

        // Users, all roles (same as above, since only 1 app)
        repId = new MymedUserId(user1);  // User 1
        rep = reputationManager.read(repId);
        assertEquals(2, rep.getNoOfRatings());
        assertEquals(0.75, rep.getReputation(), 0.01);
        repId = new MymedUserId(user2);  // AppUser 2
        rep = reputationManager.read(repId);
        assertEquals(2, rep.getNoOfRatings());
        assertEquals(0.65, rep.getReputation(), 0.01);
        repId = new MymedUserId(user3);  // AppUser 3
        rep = reputationManager.read(repId);
        assertEquals(2, rep.getNoOfRatings());
        assertEquals(0.55, rep.getReputation(), 0.01);

        // the app
        repId = new MymedAppId(appId);
        rep = reputationManager.read(repId);
        assertEquals(6, rep.getNoOfRatings());
        assertEquals(0.65, rep.getReputation(), 0.01);
    }

    /**
     * Test that the reputation depends on the last N votes
     * @throws InternalBackEndException
     */
    @Test
    public void testUpdateReputationLastN() throws InternalBackEndException {
        System.out.println("testUpdateReputationLastN");
        verdictManager.setReputationAlgorithm(new AverageReputationAlgorithm(10));
        TransactionManager.getInstance().truncate("VerdictsTimeOrdered");
        String appId = "17";
        String user1 = "user1";
        String user2 = "user2";
        IMymedRepId u1Cons = new MymedAppUserId(appId, user1, ReputationRole.Consumer);
        IMymedRepId u2Prod = new MymedAppUserId(appId, user2, ReputationRole.Producer);
        ReputationManager reputationManager = new ReputationManager();
        // u2Prod is judged 10 times at 1.0, rep should be 1.0
        for (int i = 0; i < 10; i++) {
            verdictManager.update(u1Cons, u2Prod, 1.0);
        }
        MReputationBean rep = reputationManager.read(u2Prod);
        assertEquals(10, rep.getNoOfRatings());
        assertEquals(1.0, rep.getReputation(), 0.01);
        //  5 more ratings of 0.0, rep should now be 0.5
        for (int i = 0; i < 5; i++) {
            verdictManager.update(u1Cons, u2Prod, 0.0);
        }
        rep = reputationManager.read(u2Prod);
        assertEquals(10, rep.getNoOfRatings());
        assertEquals(0.5, rep.getReputation(), 0.01);
        //  5 more ratings of 0.0, rep should now be 0.0
        for (int i = 0; i < 5; i++) {
            verdictManager.update(u1Cons, u2Prod, 0.0);
        }
        rep = reputationManager.read(u2Prod);
        assertEquals(10, rep.getNoOfRatings());
        assertEquals(0.0, rep.getReputation(), 0.01);
    }

    /**
     * Test of updateReputation method, of class VerdictManager.
     */
    @Test
    public void testUpdateReputationAvgWeighted() throws InterruptedException, InternalBackEndException {
        System.out.println("testUpdateReputationAvgWeighted");
        verdictManager.setReputationAlgorithm(new WeightedAverageReputationAlgorithm(100));
        TransactionManager.getInstance().truncate("VerdictsTimeOrdered");
        // one verdict of .8 weighted .9, 9 verdicts of .6 weighted .1, should come out to .7
        String appId = "17";
        String user1 = "user1";
        String user2 = "user2";
        // 2 performs service for 1
        //   1 rates 2 0.9, 2 rates 1 1.0
        IMymedRepId u1Cons = new MymedAppUserId(appId, user1, ReputationRole.Consumer);
        IMymedRepId u2Prod = new MymedAppUserId(appId, user2, ReputationRole.Producer);
        verdictManager.update(u1Cons, u2Prod, 0.8, 0.9);
        for (int i = 0; i < 9; i++) {
            verdictManager.update(u1Cons, u2Prod, 0.6, 0.1);
        }

        ReputationManager reputationManager = new ReputationManager();
        MReputationBean rep = reputationManager.read(u2Prod);
        assertEquals(10, rep.getNoOfRatings());
        assertEquals(0.7, rep.getReputation(), 0.01);
    }

    /**
     * Test the modality of VerdictManager in which reps are not recalculated
     * upon receipt of an update.
     */
    @Test
    public void testUpdateReputationDelayed() throws InterruptedException, InternalBackEndException {
        System.out.println("testUpdateReputationDelayed");
        verdictManager.setCalculateImmediately(false);
        TransactionManager.getInstance().truncate("VerdictsTimeOrdered");
        String appId = "17";
        String user1 = "user1";
        String user2 = "user2";
        ReputationManager reputationManager = new ReputationManager();

        IMymedRepId u1Cons = new MymedAppUserId(appId, user1, ReputationRole.Consumer);
        IMymedRepId u2Prod = new MymedAppUserId(appId, user2, ReputationRole.Producer);

        MReputationBean rep = reputationManager.read(u2Prod);
        assertEquals(0, rep.getNoOfRatings());

        for (int i = 0; i < 10; i++) {
            verdictManager.update(u1Cons, u2Prod, i / 10.0);
        }

        // The ReputationEntity hasn't been updated
        assertEquals(0, rep.getNoOfRatings());
        // calculate the reputation
        IReputationAlgorithm reputationAlgorithm = new AverageReputationAlgorithm(100);
        rep = reputationManager.calculate(u2Prod, reputationAlgorithm);
        assertEquals(10, rep.getNoOfRatings());
        assertEquals(0.45, rep.getReputation(), 0.01);
    }

    /**
     * Test of updateReputation method, of class VerdictManager.
     */
//    @Ignore
//    @Test
//    public void testUpdateReputationWeighted() throws InterruptedException, InternalBackEndException {
//        System.out.println("updateReputation");
//        IReputationAlgorithm alg = new WeightedReputationAlgorithm(100);
//        verdictManager.setReputationAlgorithm(alg);
//        TransactionManager.getInstance().truncate("VerdictsTimeOrdered");
//        TransactionManager.getInstance().truncate("ReputationEntity");
//        String appId = "app1";
//        String userId, user2Id;
//
//        for (int judger = 1; judger <= 4; judger++) {
//            userId = "user_" + judger;
//            for (int judged = 1; judged <= 4; judged++) {
//                if (judger == judged) {
//                    continue;
//                }
//                user2Id = "user_" + judged;
//                switch (judger) {
//                    case 1:   // judge 1 gives everyone a 1
//                        //verdictManager.update(appId, userId, user2Id, ReputationRole.Producer, 1.0);
//                        break;
//                    case 2: // judge 2 gives all 0's
//                        //verdictManager.update(appId, userId, user2Id, ReputationRole.Producer, 0.0);
//                        break;
//                    case 3: // judge 3 gives everyone their inverse
//                        //verdictManager.update(appId, userId, user2Id, ReputationRole.Producer, 1.0 / judged);
//                        break;
//                    case 4: // judge gives everyone a .5
//                        //verdictManager.update(appId, userId, user2Id, ReputationRole.Producer, 0.5);
//                        break;
//                }
//            }
//        }
//        // calc
//        IReputationAlgorithm reputationAlgorithm = new AverageReputationAlgorithm(100);
//        ReputationUpdaterFactory reputationUpdaterFactory = new ReputationUpdaterFactory(reputationAlgorithm);
//        ReputationManager reputationManager = new ReputationManager(wrapper);
//        double[] reps = new double[4];
//        int[] num = new int[4];
//        for (int user = 1; user <= 4; user++) {
//            userId = "user_" + user;
//            String repEntityId = MymedRepId.MakeId(appId, userId, ReputationRole.Producer);
//            MReputationBean readReputation = reputationManager.read(repEntityId);
//            reps[user - 1] = readReputation.getReputation();
//            num[user - 1] = readReputation.getNoOfRatings();
//            System.out.println(userId + ": " + reps[user - 1] + " num: " + num[user - 1]);
//            repEntityId = MymedRepId.MakeId(appId, userId);
//            readReputation = reputationManager.read(repEntityId);
//            System.out.println(userId + ": " + readReputation.getReputation());
//        }
//        if (true) {
//            return;
//        }
//        // let's have a loop to test
//        for (int i = 0; i < 4; i++) {
//            for (int user = 1; user <= 4; user++) {
//                userId = "user_" + user;
//                String repEntityId = MymedRepId.MakeId(appId, userId, ReputationRole.Producer);
//                String personId = MymedRepId.MakeId(appId, userId);
//
//                MReputationBean newRep = alg.computeReputation(repEntityId);
//                System.out.println(i + ": " + repEntityId + "\t" + newRep.getReputation());
//                TransactionManager.getInstance().createTransaction();
//                ReputationEntity re = ReputationEntity.read(repEntityId);
//                re.setReputation(newRep.getReputation());
//                re.setNumberOfVerdicts(newRep.getNoOfRatings());
//                re.persist();
//                TransactionManager.getInstance().commit();
//
//                newRep = alg.computeReputation(personId);
//                System.out.println(i + ": " + personId + "\t" + newRep.getReputation());
//                TransactionManager.getInstance().createTransaction();
//                re = ReputationEntity.read(personId);
//                re.setReputation(newRep.getReputation());
//                re.setNumberOfVerdicts(newRep.getNoOfRatings());
//                re.persist();
//                TransactionManager.getInstance().commit();
//
//            }
//        }
//
//        // now, test median reputation algorithm
//        verdictManager.setReputationAlgorithm(new WeightedAverageReputationAlgorithm(100));
//        assertEquals(0.455, reps[0], .03);
//        assertEquals(0.674, reps[1], .03);
//        assertEquals(0.427, reps[2], .03);
//        assertEquals(0.367, reps[3], .03);
//
//    }

}
