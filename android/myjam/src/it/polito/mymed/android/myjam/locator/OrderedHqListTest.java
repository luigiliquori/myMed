package it.polito.mymed.android.myjam.locator;

import java.util.Random;

import junit.framework.TestCase;

public class OrderedHqListTest extends TestCase {

	public void testAddOrdered() {
		OrderedHqList testList = new OrderedHqList();
		HilbertQuad testHQ = new HilbertQuad(0, (short) 0, 0, 0, 0, 0, null);
		testList.addOrdered(new HqWrapper((short) 10,testHQ));
		Random random = new Random();
		for (int i=0;i<10000;i++){
			short metric = (short) (random.nextInt(1000)-1);
			testList.addOrdered(new HqWrapper(metric,testHQ));
		}
		HqWrapper oldQi = null;
		for (int i=0;i<testList.size();i++){
			if (oldQi!=null)
				if (oldQi.getMetric()>testList.get(i).getMetric())
					fail(String.valueOf(oldQi.getMetric()) + " is not less then " +
							String.valueOf(testList.get(i).getMetric()));
			oldQi=testList.get(i);
		}		
	}

}
