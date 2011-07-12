package com.mymed.myjam.type;

import java.util.List;
/**
 * Used to contain details about reports with their feedbacks.
 * @author iacopo
 *
 */
public class ReportInfo{
	private MReportBean report;
	private List<MFeedBackBean> feedbacks;

	public ReportInfo(){}

	/**Getter and Setter*/
	public void setReport(MReportBean report) {
		this.report = report;
	}

	public MReportBean getReport() {
		return report;
	}
	
	public void setFeedbacks(List<MFeedBackBean> feedbacks) {
		this.feedbacks = feedbacks;
	}

	public List<MFeedBackBean> getFeedbacks() {
		return feedbacks;
	}
}
