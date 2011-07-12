package com.mymed.android.myjam.type;

import java.util.List;
import java.util.Map;
/**
 * Used to contain details about reports.
 * @author iacopo
 *
 */
public class ReportInfo{
	private MReportBean report;
	private List<MReportBean> updates;
	private Map<String,List<MFeedBackBean>> feedbacks;

	public ReportInfo(){}

	/**Getter and Setter*/
	public void setReport(MReportBean report) {
		this.report = report;
	}

	public MReportBean getReport() {
		return report;
	}
	
	public void setFeedbacks(Map<String,List<MFeedBackBean>> feedbacks) {
		this.feedbacks = feedbacks;
	}

	public Map<String,List<MFeedBackBean>> getFeedbacks() {
		return feedbacks;
	}
	
	public void setUpdates(List<MReportBean> updates) {
		this.updates = updates;
	}

	public List<MReportBean> getUpdates() {
		return updates;
	}
}
