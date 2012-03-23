/*
 * To change this template, choose Tools | Templates and open the template in
 * the editor.
 */

package com.mymed.controller.core.manager.reputation.api.mymed_ids;

import java.util.ArrayList;

/**
 * The interface a Class must implement to be usable as a Mymed reputation ID.
 * It must provide an ArrayList of Strings to be used as reputation entity IDs,
 * and must distinguish one of these as the primary ID.
 * 
 * @author Peter Neuss
 */
public interface IMymedRepId {

    String getPrimaryId();

    ArrayList<String> getEntityIds();
}
