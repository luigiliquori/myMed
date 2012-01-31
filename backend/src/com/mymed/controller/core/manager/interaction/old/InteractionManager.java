/*
 * Copyright 2012 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
package com.mymed.controller.core.manager.interaction.old;

import java.io.UnsupportedEncodingException;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.reputation.old.ReputationManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.interaction.MInteractionBean;
import com.mymed.model.data.reputation.old.MReputationBean;

/**
 * Manage the reputation of a user
 * 
 * @author lvanni
 * @author Milo Casagrande
 */
public class InteractionManager extends AbstractManager implements IInteractionManager {

  private static final String CF_INTERACTION = COLUMNS.get("column.cf.interaction");
  private static final String COLUMN_ID = COLUMNS.get("column.id");
  private static final String SC_USER_LIST = COLUMNS.get("column.sc.user.list");
  private static final String SC_INTERACTION_LIST = COLUMNS.get("column.sc.interaction.list");

  private final ReputationManager reputationManager;

  public InteractionManager() throws InternalBackEndException {
    this(new StorageManager());
  }

  public InteractionManager(final StorageManager storageManager) throws InternalBackEndException {
    super(storageManager);
    reputationManager = new ReputationManager();
  }

  @Override
  public void create(final MInteractionBean interaction) throws InternalBackEndException, IOBackEndException {
    try { // Verify the interaction does not exist
      storageManager.selectColumn(CF_INTERACTION, interaction.getId(), COLUMN_ID);
    } catch (final IOBackEndException e) {
      // INTERACTION CREATION
      storageManager.insertSlice(CF_INTERACTION, interaction.getId(), interaction.getAttributeToMap());
      if (interaction.getFeedback() != -1) {
        // REPUTATION UPDATE
        final MReputationBean reputation = reputationManager.read(interaction.getProducer(), interaction.getConsumer(),
            interaction.getApplication());
        // count the number of interaction
        // int count = storageManager.countColumns(SC_INTERACTION_LIST,
        // reputation.getInteractionList());
        final int count = reputation.getNbInteraction();
        final double reputationValue = (reputation.getValue() + interaction.getFeedback()) / (count + 1);
        reputation.setValue(reputationValue);
        // update the reputation
        reputationManager.update(reputation, interaction.getApplication() + interaction.getProducer());
        try {
          // update the interaction list
          storageManager.insertSuperColumn(SC_INTERACTION_LIST, reputation.getInteractionList(), interaction.getId(),
              COLUMN_ID, interaction.getId().getBytes(ENCODING));
          // update the rater list
          storageManager.insertSuperColumn(SC_USER_LIST, reputation.getRaterList(), interaction.getConsumer(), "user",
              interaction.getConsumer().getBytes(ENCODING));
        } catch (final UnsupportedEncodingException e1) {
          throw new InternalBackEndException(e1.toString());
        }
      }
      return;
    }
    throw new IOBackEndException("Interaction already exist!", 409);
  }

  @Override
  public MInteractionBean read(final String interactionID) throws InternalBackEndException, IOBackEndException {
    final Map<byte[], byte[]> args = storageManager.selectAll(CF_INTERACTION, interactionID);

    return (MInteractionBean) introspection(MInteractionBean.class, args);
  }

  @Override
  public void update(final MInteractionBean interaction) throws InternalBackEndException, IOBackEndException {
    create(interaction);
  }

  @Override
  public void delete(final String interactionID) throws InternalBackEndException {
    storageManager.removeAll(CF_INTERACTION, interactionID);
  }
}
