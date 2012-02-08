package com.mymed.utils.locator;


/**
 * Class which wraps {@link HilbertQuad} adding a metric to be used to keep
 * ordered the objects on the {@link OrderedHqList}.
 * 
 * @author iacopo
 *
 */
public class HqWrapper {
	private short metric;
	private HilbertQuad quad;
	
	public HqWrapper(short metric,HilbertQuad quad){
		this.setMetric(metric);
		this.setQuad(quad);
	}

	public void setMetric(short metric) {
		this.metric = metric;
	}

	public short getMetric() {
		return metric;
	}

	public void setQuad(HilbertQuad quad) {
		this.quad = quad;
	}

	public HilbertQuad getQuad() {
		return quad;
	}
}
