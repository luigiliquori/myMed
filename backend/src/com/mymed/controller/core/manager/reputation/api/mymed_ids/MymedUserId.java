/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.mymed.controller.core.manager.reputation.api.mymed_ids;

/**
 * A MymedId specific to a Mymed user (across all apps)
 * @author peter
 */
public class MymedUserId extends MymedRepId {

    public MymedUserId(String user) {
        primaryId = MakeUserId(user);
        allIds.add(primaryId);
    }
}
