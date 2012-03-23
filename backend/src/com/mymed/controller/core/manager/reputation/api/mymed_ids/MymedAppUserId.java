/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.mymed.controller.core.manager.reputation.api.mymed_ids;

/**
  * A MymedId specific to a Mymed app/user pair
* @author peter
 */
public class MymedAppUserId extends MymedRepId {

    /**
     * A Mymed Id for the user of an app
     * @param app - the app
     * @param user - the user
     */
    public MymedAppUserId(String app, String user) {
        primaryId = MakeId(app, user);
        allIds.add(primaryId);
        allIds.add(MakeAppId(app));
        allIds.add(MakeUserId(user));
    }

    /**
     * A Mymed Id for a specific role of a user of an app
     * @param app - the app
     * @param user - the user
     * @param role - the role
     */
    public MymedAppUserId(String app, String user, ReputationRole role) {
        primaryId = MakeId(app, user, role);
        allIds.add(primaryId);
        allIds.add(MakeId(app, user));
        allIds.add(MakeAppId(app));
        allIds.add(MakeUserId(user));
    }

    /**
     * A Mymed Id for an app-specific specialization of a user/role for an app.
     *
     * @param app - the app
     * @param user - the user
     * @param role - the role
     * @param ability - the specialization
     */
    public MymedAppUserId(String app, String user, ReputationRole role, String ability) {
        primaryId = MakeId(app, user, role, ability);
        allIds.add(primaryId);
        allIds.add(MakeId(app, user, role));
        allIds.add(MakeId(app, user));
        allIds.add(MakeAppId(app));
        allIds.add(MakeUserId(user));
    }
}
