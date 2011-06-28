package it.polito.mymed.android.myjam.locator;

import java.util.Comparator;

/*
 *  The QuadIndex with the smallest metric comes first. If the metric is the same but the HilbertQuads are not
 *  equals is inserted first the one with the smallest index.
 */
public class QuadIndexComparator implements Comparator<HqWrapper> {
	@Override
	public int compare(HqWrapper arg0, HqWrapper arg1) {
		short deltaMetric =  (short) (arg0.getMetric() - arg1.getMetric());
		if (deltaMetric==0){
			boolean equals = arg0.getQuad().equals(arg1.getQuad());
			if (equals)
				return 0;
			else
				return Long.signum(arg0.getQuad().getIndex()-arg1.getQuad().getIndex());
		}else
			return deltaMetric;
	}

}
