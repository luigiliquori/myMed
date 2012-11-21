<div data-role="page">

	<? print_header_bar(true, false); ?>
	
	<div data-role="content" >
		<b>Author</b> : <?= $this->result->publisherID ?><br/>
		<b>Begin</b> : <?= $this->result->begin ?><br/>
		<b>End</b>: <?= $this->result->end ?><br/>
		<b>Pred1</b>: <?= $this->result->pred1 ?><br/>
		<b>Pred2</b>: <?= $this->result->pred2 ?><br/>
		<b>Pred3</b>: <?= $this->result->pred3 ?><br/>
		<b>Wrapped1</b>: <?= $this->result->wrapped1 ?><br/>
		<b>Wrapped2</b>: <?= $this->result->wrapped2 ?><br/>
		<b>Data1</b>: <?= $this->result->data1 ?><br/>
		<b>Data2</b>: <?= $this->result->data2 ?><br/>
		<b>Data3</b>: <?= $this->result->data3 ?><br/>
	</div>
	
	<? print_footer_bar_main("#find"); ?>
	
</div>

<? include_once 'MainView.php'; ?>
<? include_once 'FindView.php'; ?>
<? include_once 'ResultView.php'; ?>
<? include_once 'ProfileView.php'; ?>
<? include_once 'UpdateProfileView.php'; ?>
