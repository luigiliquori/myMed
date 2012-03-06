import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertFalse;

import java.util.UUID;

import org.junit.Test;

import com.mymed.utils.TimeUuid;

/**
 * @author peter
 */
public class UuidTest {
    /**
     * Let's just make a few uuids, and test that consecutive uuids are
     * different
     */
    @Test
    public void createUuid() {
        UUID lastId = null;
        for (int i = 0; i < 10; i++) {
            final UUID id = TimeUuid.getTimeUUID();
            System.out.println(id);
            if (lastId != null) {
                assertFalse(lastId.equals(id));
            }
            lastId = id;
        }
    }

    @Test
    public void uuidConversion() {
        for (int i = 0; i < 10; i++) {
            final UUID id = TimeUuid.getTimeUUID();
            final byte[] ba = TimeUuid.asByteArray(id);
            final UUID id2 = TimeUuid.toUUID(ba);
            assertEquals(id, id2);
        }
    }
}
