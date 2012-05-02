<div id="home" align="center">
	
	<!-- MENU -->
	<div id="menu" style="position:relative; top:20px; left:20px; text-align: left; z-index: 99">
		<table>
		  <tr>
		    <td style="border: none;">Architecture:</td>
		  </tr>
		  <tr>
		    <td style="background-color: #00FF50;"><a id="NetworkB" href="#" onclick="" onmouseover="changeArchiView('Network');">myMed Network</a></td>
		  </tr>
		  <tr>
		    <td style="background-color: #64FF50;"><a id="BackBoneB" href="#" onclick="displayArchi('BackBone')" onmouseover="changeArchiView('BackBone');">BackBone</a></td>
		  </tr>
		  <tr>
		    <td style="background-color: #9BFF50;"><a id="NodeB" href="#" onclick="displayArchi('Node')" onmouseover="changeArchiView('Node');">Node</a></td>
		  </tr>
		  <tr>
		    <td style="background-color: #C8FF50;"><a id="FrontEndB" href="#" onclick="displayArchi('FrontEnd')" onmouseover="changeArchiView('FrontEnd');">FrontEnd</a></td>
		  </tr>
		  <tr>
		    <td style="background-color: #FFFF50;"><a id="BackEndB" href="#" onclick="displayArchi('BackEnd')" onmouseover="changeArchiView('BackEnd');">BackEnd</a></td>
		  </tr>
		  <tr>
		    <td style="background-color: #FFE650;"><a id="VirtualMachineB" href="#" onclick="displayArchi('VirtualMachine')" onmouseover="changeArchiView('VirtualMachine');">Virtual Machine</a></td>
		  </tr>
		  <tr>
		    <td style="background-color: #FFC850;"><a id="OperatingSystemB" href="#" onclick="displayArchi('OperatingSystem')" onmouseover="changeArchiView('OperatingSystem');">Operating System</a></td>
		  </tr>
		  <tr>
		    <td style="background-color: #FF6650;"><a id="KernelB" href="#" onclick="displayArchi('Kernel')" onmouseover="changeArchiView('Kernel');">Kernel</a></td>
		  </tr>
		  <tr>
		    <td style="background-color: #FF0050;"><a id="PhysicalMachineB" href="#" onclick="displayArchi('PhysicalMachine')" onmouseover="changeArchiView('PhysicalMachine');">Physical Machine</a></td>
		  </tr>
		</table>
	</div>
		
	<!-- CONTENT -->
	<div id="View" style="position:relative; top:-150px; height: 400px;">
		
		<!-- ARCHIVIEW -->
		<img id="NetworkView" src="img/NetworkView.png" width="600px">
		<img id="BackBoneView" Style="display: none;" src="img/BackboneView.png" width="600px">
		<img id="NodeView" Style="display: none;" src="img/NodeView" width="600px">
		<img id="FrontEndView" Style="display: none;" src="img/FrontEndView" width="600px">
		<img id="BackEndView" Style="display: none;" src="img/BackEndView" width="600px">
		<img id="VirtualMachineView" Style="display: none;" src="img/VirtualMachineView" width="600px">
		<img id="OperatingSystemView" Style="display: none;" src="img/OSView" width="600px">
		<img id="KernelView" Style="display: none;" src="img/KernelView" width="600px">
		<img id="PhysicalMachineView" Style="display: none;" src="img/PhysicalView" width="600px">
	</div>
	
	<!-- ARCHI -->
	
	<!-- NETWORK -->
	<div id="Network" Style="width: 1000px; display: None;">
		<div style="text-align: left;">
			<a href="#" onclick="closeArchi('Network');"> Go Back </a>
		</div>
		<h2>myMed Network</h2>
		User + BackBone
	</div>
	
	<!-- BACKBONE -->
	<div id="BackBone" Style="width: 1000px; display: None;">
		<div style="text-align: left;">
			<a href="#" onclick="closeArchi('BackBone');"> Go Back </a>
		</div>
		<h2>BackBone</h2>
		25 PCs Francais<br>
		+ <br>
		25 PCs Italiens<br><br>
		<img alt="" src="img/BackBone.png" width="1000px">
	</div>
		
	<!-- NODE -->
	<div id="Node" Style="width: 1000px; display: None;">
		<div style="text-align: left;">
			<a href="#" onclick="closeArchi('Node');"> Go Back </a>
		</div>
		<h2>Node</h2>
		<img src="img/Node.png" width="1000px;">
	</div>
	
	<!-- FRONTEND -->
	<div id="FrontEnd" Style="width: 1000px; display: None;">
		<div style="text-align: left;">
			<a href="#" onclick="closeArchi('FrontEnd');"> Go Back </a>
		</div>
		<h2>FrontEnd</h2>
		<img src="img/FrontEnd.png" width="1000px;">
	</div>
	
	<!-- BACKTEND -->
	<div id="BackEnd" Style="width: 1000px; display: None;">
		<div style="text-align: left;">
			<a href="#" onclick="closeArchi('BackEnd');"> Go Back </a>
		</div>
		<h2>BackEnd</h2>
		<img src="img/BackEnd.png" width="1000px;">
	</div>
	
	<!-- VIRTUALMACHINE -->
	<div id="VirtualMachine" Style="width: 1000px; display: None;">
		<div style="text-align: left;">
			<a href="#" onclick="closeArchi('VirtualMachine');"> Go Back </a>
		</div>
		<h2>VirtualMachine</h2>
		Need to be defined by UNIPO...<br>
		<h2>Will be Managed and Monitored with:</h2>
		<table Style="text-align: center;">
		  <tr>
		    <th><img src="img/eucalyptus.png"></th>
		    <th><img src="img/cacti.png"></th>
		  </tr>
		  <tr>
		    <th><a href="http://open.eucalyptus.com/wiki/presentations">eucalyptus</a></th>
		    <th><a href="http://mymed2.sophia.inria.fr/cacti">cacti</a></th>
		  </tr>
		</table>

	</div>
	
	<!-- OPERATINGSYSTEM -->
	<div id="OperatingSystem" Style="width: 1000px; display: None;">
		<div style="text-align: left;">
			<a href="#" onclick="closeArchi('OperatingSystem');"> Go Back </a>
		</div>
		<h2>OperatingSystem</h2>
		<img src="img/OS.jpeg">
	</div>

	<!-- KERNEL -->
	<div id="Kernel" Style="width: 1000px; display: None;">
		<div style="text-align: left;">
			<a href="#" onclick="closeArchi('Kernel');"> Go Back </a>
		</div>
		<h2>Kernel</h2>
		<img src="img/OS.jpeg" >
	</div>

	<!-- PHYSICALMACHINE -->
	<div id="PhysicalMachine" Style="width: 1000px; display: None;">
		<div style="text-align: left;">
			<a href="#" onclick="closeArchi('PhysicalMachine');"> Go Back </a>
		</div>
		<h2>PhysicalMachine</h2>
		<img src="img/Hardware1.jpeg" ><br>
		<img src="img/Hardware2.jpeg" >
	</div>

</div>