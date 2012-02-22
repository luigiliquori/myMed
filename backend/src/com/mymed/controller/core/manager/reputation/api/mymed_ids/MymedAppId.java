/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.mymed.controller.core.manager.reputation.api.mymed_ids;

/**
 * A MymedId specific to a Mymed app
 * @author peter
 */
public class MymedAppId extends MymedRepId {

    public MymedAppId(String app) {
        primaryId = MakeAppId(app);
        allIds.add(primaryId);
    }

    public MymedAppId(String app, String appSpecificUid) {
        primaryId = MakeAppId(app, appSpecificUid);
        allIds.add(primaryId);
        allIds.add(MakeAppId(app));
    }
}
