/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.api.mymed_ids;

/**
 * This class is for IDs which should be used only by the Mymed platform
 * itself.  For example, it could be used to create an Id for each application,
 * so that the system can ask users for explicit verdicts of each application.
 * The application itself should not be able to give itself a vote.
 * @author Peter Neuss
 */
public class MymedSystemId extends MymedRepId {

    public MymedSystemId(String uniqueId) {
        primaryId = MakeUserId(uniqueId);
        allIds.add(primaryId);
    }
}
