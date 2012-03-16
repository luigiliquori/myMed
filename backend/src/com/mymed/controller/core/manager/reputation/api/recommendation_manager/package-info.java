/**
 * This package contains the principal Classes for interfacing with the
 * reputation system.
 * <h3>Code Snippets</h3>
 * <p>
 *   A typical way for an app to add a verdict.
 * <pre>
 *   VerdictManager vm = new VerdictManager();
 *   // construct the IDs
 *   IMymedRepId judge = new MymedAppUserId(appId, user1, ReputationRole.Consumer);
 *   IMymedRepId judged = new MymedAppUserId(appId, user2, ReputationRole.Producer);
 *   // add a verdict (consumer judges producer)
 *   vm.update(user1Consumer, user2, 0.8);
 * </pre>
 * </p>
 * <p>
 *   Calculate the reputation of user1 of app1 as a producer
 * <pre>
 *   // construct the ID
 *   IMymedRepId judged = new MymedAppUserId(app1, user1, ReputationRole.Producer);
 *   // create the algorithm
 *   IReputationAlgorithm reputationAlgorithm = new AverageReputationAlgorithm(100);
 *   // calculate with reputation manager
 *   ReputationManager rep = new ReputationManager();
 *   MReputationBean rep = reputationManager.calculate(u2Prod, reputationAlgorithm);
 * </pre>
 * </p>
 */
package com.mymed.controller.core.manager.reputation.api.recommendation_manager;
