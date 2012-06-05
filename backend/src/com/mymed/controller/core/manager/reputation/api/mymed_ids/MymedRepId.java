/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.mymed.controller.core.manager.reputation.api.mymed_ids;

import com.mymed.controller.core.manager.reputation.globals.PKConstants;
import java.util.ArrayList;


/**
 *
 * A base class with an implementation of IMymedRepId.  It also provides
 * static helper functions useful to subclasses (and ensuring consistency
 * among them).
 *
 * The reputation system is independent of any higher level semantics of
 * application, user, etc.  It deals only with "ReputationEntities", which
 * are basically just UIDs. Each Verdict has two ReputationEntities, the
 * judge and the judged.  A judge might be the user of an application, or
 * (in the example of MyJam) it might be a system function which gauges the
 * correctness of a user's action.  Similarly, the judged might be the user of
 * an application in a role (producer/consumer). However what is really judged
 * is an ability...for example, in MyTranslate, it would make sense for a translator
 * to have different votes according the the languages translated, and even the direction
 * (I do better at Italian->English than English->Italian)
 *
 * This class offers methods for the creation of UIDs for the reputation system.
 * Typically, the judge will be an app/user combination, and the judged will be
 * an app/user/role combination.  However, there are functions allowing more
 * flexibility.
 *
 * @author Peter Neuss
 */

public class MymedRepId implements IMymedRepId{

    protected String primaryId;
    protected ArrayList<String> allIds = new ArrayList<String>(8);

    @Override
    public ArrayList<String> getEntityIds() {
        return allIds;
    }

    @Override
    public String getPrimaryId() {
        return primaryId;
    }


    protected static String roleToString(ReputationRole role) {
        switch (role) {
            case Producer: return "p";
            case Consumer: return "c";
            default: return "";
        }
    }

    public static String MakeId(String appId, String userId) {
        return AppUserPrefix + PKConstants.SEPARATOR_CHAR + appId + PKConstants.SEPARATOR_CHAR
                + userId;
    }

    public static String MakeId(String appId, String userId, ReputationRole role) {
        return AppUserPrefix + PKConstants.SEPARATOR_CHAR + appId + PKConstants.SEPARATOR_CHAR
                + userId + PKConstants.SEPARATOR_CHAR + roleToString(role);
    }

    protected static String MakeId(String appId, String userId, ReputationRole role, String ability) {
        return AppUserPrefix + PKConstants.SEPARATOR_CHAR + appId + PKConstants.SEPARATOR_CHAR
                + userId + PKConstants.SEPARATOR_CHAR + roleToString(role) +
                PKConstants.SEPARATOR_CHAR + ability;
    }

    protected static String MakeAppId(String appId) {
        return AppPrefix + PKConstants.SEPARATOR_CHAR + appId;
    }

    protected static String MakeAppId(String appId, String appUniqueId) {
        return AppPrefix + PKConstants.SEPARATOR_CHAR + appId + PKConstants.SEPARATOR_CHAR
                + appUniqueId;
    }

    protected static String MakeUserId(String personId) {
        return UserPrefix + PKConstants.SEPARATOR_CHAR + personId;
    }

    protected static String MakeSystemId(String uniqueId) {
        return SystemPrefix + PKConstants.SEPARATOR_CHAR + uniqueId;
    }

    protected static final String AppUserPrefix = "00";  ///  app/user combinations
    protected static final String AppPrefix = "01";  /// just app related
    protected static final String UserPrefix = "02";  /// just person related
    protected static final String SystemPrefix = "03"; /// for system use only


}
