<div id="vocabulary" align="center">
	
	<table>
	  <tr>
	    <th align="left">Key-Word</th>
	    <th align="left">definition</th>
	  </tr>
	  <tr>
	    <td>BackBone</td>
	    <td>The mymed Network is based on a distributed architecture built on top of 50 PCs. 25 are installed in France, 25 in Italie. 
	    Each machine is called "Node" and the name is chosen between mymed1 and mymed50. Each node should have:<br>
	    <ul>
		  <li>A Public IP</li>
		  <li>A large bandwith</li>
		  <li>A good security level</li>
		  <li>A good availability (Should be always up)</li>
		</ul>
		The BackBone is responsible for the persistance of the "myMed data".
	    </td>
	  </tr>
	  <tr>
	    <td>Node</td>
	    <td>A myMed Node is one machine of the backbone. It should provide webServices (BackEnd part) and a web graphical user interface (FronEnd part) for the myMed "lightweight client".
	    A node should manage this services with different Virtual Machines.
	    </td>
	  </tr>
	  <tr>
	    <td>FrontEnd</td>
	    <td>The FrontEnd is the web user interface used by the users to access to myMed. It's made from html/php5/css/javascript and it use the BackEnd REST API to
	    store and retreive data</td>
	  </tr>
	  <tr>
	    <td>BackEnd</td>
	    <td>The BackEnd is the core of myMed. It provide web services and use a distributed data storage mecanism. The langage used is Java JEE, and the web server chosen
	    is GlassFish v3.</td>
	  </tr>
	  <tr>
	    <td>myMed data</td>
	    <td>The data storage mecasim is DHT based. The dataStructure is made from no-sql, the consistency and the load balancing is managed by the DHT node. 
	    myMed will use apche Cassandra (>= 0.7) as default DHT.</td>
	  </tr>
	  <tr>
	    <td>Client</td>
	    <td>A Client is a Graphical User Interface for the user to access to myMed. This client could embed a part of the myMed engine (see STEP 2 with a p2p myMed network)</td>
	  </tr>
	  <tr>
	    <td>Lightweight Client</td>
	    <td>A Lightweight Client means "without intelligence", it's a pure Graphical User Interface like the web user interface provided by the frontend.</td>
	  </tr>
	  <tr>
	    <td>Application</td>
	    <td>An application, in myMed, provide to the users a mecanism for matching supply with demand in a specific context. Example of applications:<br>
	    <ul>
		  <li>myTransport: A "Producer" provide a trip to make car sharing with his car, and a "Consumer" ask to join this trip</li>
		  <li>myMountain: A "Producer" provide pictures of montains, and a consumer search nice pictures related to GPS coordinates + a season</li>
		  <li>...</li>
		</ul>
		 The name application sould be made with the prefix "my".
	    </td>
	  </tr>
	  <tr>
	    <td>Producer</td>
	    <td>The user or the application who provide data, informations or personnal service in a given application</td>
	  </tr>
	  <tr>
	    <td>Consumer</td>
	    <td>The user who will consume a service provided by a producer.</td>
	  </tr>
	  <tr>
	    <td>Service</td>
	    <td>The "Service" word used in the backend context means the part of the myMed source code wich provide a specific job for the myMed engine. example:<br>
	    <ul>
		  <li>The ProfileManager: provide an API to manage the user profile</li>
		   <li>The SessionManger: provide an API to manage a user session</li>
		  <li>The ReputationManager: provide an API to manage the reputation of an user in a given service</li>
		  <li>...</li>
		</ul>
	    </td>
	  </tr>
	  <tr>
	    <td>Manager</td>
	    <td>The managers provide an API for all the component of the myMed engine</td>
	  </tr>
	  <tr>
	    <td>RequestHandler</td>
	    <td>A RequestHandler provide an API for the frontend, each RequestHandler is related to a specific service of the backend</td>
	  </tr>
	  <tr>
	    <td>REST API</td>
	    <td>The REST API, is the API used by the frontend to dialog with the BackEnd.</td>
	  </tr>
	  <tr>
	    <td>Interaction</td>
	    <td>An interaction is created when a consumer contact or use the service provided by a producer. An Interaction is one to one, but  myMed could create "Complex Interaction" 
	    based on many Interaction (will be a list of Interaction).</td>
	  </tr>
	  <tr>
	    <td>Wrapper</td>
	    <td>A Wrapper provide the API to use the DHT data storage mecanism chosen by myMed. The Wrapper is responsible for the transport layer.</td>
	  </tr>
	  <tr>
	    <td>Model</td>
	    <td>The model is the myMed data structure. The data stored into myMed could be represented as an MBean object</td>
	  </tr>
	  <tr>
	    <td>View</td>
	    <td>The view is a myMed client. It's an user graphical interface for the myMed users</td>
	  </tr>
	  <tr>
	    <td>Controller</td>
	    <td>The controller is the core of myMed. It provide all the service and components needed by myMed</td>
	  </tr>
	  <tr>
	    <td>MBean</td>
	    <td>An MBean is an java object which represent an entry in the myMed data structure.</td>
	  </tr>
	</table>
	
	<!-- MORE INFO -->
	<div id="more">
	
	</div>

</div>
