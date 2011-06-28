package it.polito.mymed.android.myjam.locator;

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
