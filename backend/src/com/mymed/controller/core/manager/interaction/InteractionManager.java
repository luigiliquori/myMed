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
package com.mymed.controller.core.manager.interaction;

import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.MymedAppUserId;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.ReputationRole;
import com.mymed.controller.core.manager.reputation.api.recommendation_manager.VerdictManager;
import com.mymed.controller.core.manager.reputation.recommendation_manager.AverageReputationAlgorithm;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.interaction.MInteractionBean;

/**
 * Manage the reputation of a user
 * 
 * @author lvanni
 * @author Milo Casagrande
 */
public class InteractionManager extends AbstractManager implements IInteractionManager {

  private static final String CF_INTERACTION = COLUMNS.get("column.cf.interaction");
  private static final String COLUMN_ID = COLUMNS.get("column.id");

  private final VerdictManager verdictManager;

        public static final int LAST_N = 100;
        
  public InteractionManager() throws InternalBackEndException {
    this(new StorageManager());
  }

  public InteractionManager(final StorageManager storageManager) throws InternalBackEndException {
    super(storageManager);
		this.verdictManager = new VerdictManager(new AverageReputationAlgorithm(LAST_N));
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
                                MymedAppUserId judge = new MymedAppUserId(interaction.getApplication(), 
                                        interaction.getConsumer(), ReputationRole.Consumer);
                                MymedAppUserId charged = new MymedAppUserId(interaction.getApplication(),
                                        interaction.getProducer(), ReputationRole.Producer);
				if (!verdictManager.update(judge,charged,interaction.getFeedback())) {
          throw new InternalBackEndException("Reputation update: doesn't work!");
        }
      }
      return;
    }
    throw new IOBackEndException("Interaction already exist!", 409);
  }

  @Override
  public MInteractionBean read(final String interactionID) throws InternalBackEndException, IOBackEndException {
    final Map<byte[], byte[]> args = storageManager.selectAll(CF_INTERACTION, interactionID, false);

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
