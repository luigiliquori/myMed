<div id="workflow" align="left">

	<p>
		The starting point is to clone the public repository from INRIA gforge:
	</p>
	
	<b>git clone git://scm.gforge.inria.fr/mymed/mymed.git</b><br>
	
	<p>
		You can see in the mymed folder 2 parts:
		<ul>
		  <li><b>frontend</b> (for the view)</li>
		  <li><b>backend</b>  (for the engine)</li>
		</ul>
	</p>
	
	<p>
		The frontend is based on html/javascrip/css, and need a php server.
		The backend is in JEE, the server used is glassfish v3.
		To Compile the source code you need some libraries, you can find all of them in :
	</p> 
	
	<b>mymed/backend/lib/</b><br>
	
	<p>
		The backend is exported as *.war file for glassfish
	</p>
	
	<p>
		The workflow: <br>
		<img alt="workflow" src="img/workflow.png">
	</p>
	
	<p>
		The workflow chosen is Integration-Manager:<br>
		"Because Git allows you to have multiple remote repositories, it’s possible to have a workflow where each developer has write access to their own public repository and read access to everyone else’s. This scenario often includes a canonical repository that represents the “official” project. To contribute to that project, you create your own public clone of the project and push your changes to it. Then, you can send a request to the maintainer of the main project to pull in your changes. They can add your repository as a remote, test your changes locally, merge them into their branch, and push back to their repository. The process works as follows:
			<ul>
			   <li>The project maintainer pushes to their public repository.</li>
			   <li>A contributor clones that repository and makes changes.</li>
			   <li>The contributor pushes to their own public copy.</li>
			   <li>The contributor sends the maintainer an e-mail asking them to pull changes.</li>
			   <li>The maintainer adds the contributor’s repo as a remote and merges locally.</li>
			   <li>The maintainer pushes merged changes to the main repository." </li>
			</ul>
		<br>
		see: http://progit.org/book/ch5-1.html
	</p>
	
	<p>
		To configure your repository for this workflow folow this command line:<br>
		<b>
			cd mymed/<br>
			git remote rm origin<br>
			git remote add mymed git://scm.gforge.inria.fr/mymed/mymed.git<br>
			git remote add polito git+ssh://username@scm.gforge.inria.fr//gitroot//mymed/polito.git<br>
			git remote add unipo git+ssh://username@scm.gforge.inria.fr//gitroot//mymed/unipo.git<br>
			git remote add unito git+ssh://username@scm.gforge.inria.fr//gitroot//mymed/unito.git<br>
		</b>
	</p>
	
	<p>
		At the end the command: <br>
		<b>git remote -v</b>
		should give you:<br>
		<b>
			mymed    git://scm.gforge.inria.fr/mymed/mymed.git (fetch)<br>
			mymed    git://scm.gforge.inria.fr/mymed/mymed.git (push)<br>
			polito      git+ssh://username@scm.gforge.inria.fr//gitroot//mymed/polito.git (fetch)<br>
			polito      git+ssh://username@scm.gforge.inria.fr//gitroot//mymed/polito.git (push)<br>
			unipo     git+ssh://username@scm.gforge.inria.fr//gitroot//mymed/unipo.git (fetch)<br>
			unipo     git+ssh://username@scm.gforge.inria.fr//gitroot//mymed/unipo.git (push)<br>
			unito      git+ssh://username@scm.gforge.inria.fr//gitroot//mymed/unito.git (fetch)<br>
			unito      git+ssh://username@scm.gforge.inria.fr//gitroot//mymed/unito.git (push)<br>
		</b>
	</p>
	
	<p>
		For now all the pulic repositories are on gforge, but it's up to you to move it on an other server,
		with specific permission. (Note that gforge don't allows you to change the permission on your repository)
	</p>
	
	<p>
		Then manage your developers by using the branch mecanism and keep your master branch clean.
	</p>
	<p>
		see: http://www.kernel.org/pub/software/scm/git/docs/user-manual.html
	</p>
	
	<p>
		EXAMPLE of usage:<br>
		<b>git status</b>              # to see if you need to commit before<br>
		<b>git pull mymed master</b>   # to be synchronized with the main repository (mymed.git)<br>
		<b>git log</b>                 # to be aware of the news<br>
		<b>git branch dev</b>          # to create a new branch called "dev" on your local repository <br>
		<b>git checkout dev</b>        # to switch on your branch "dev"<br>
	</p>
	<p>
		# work in progress...
	</p>
	
	<p>
		<b>git status  </b>                         # to see your changes<br>
		<b>git add <files>  </b>                    # to add new files if necessary <br>
		<b>git commit -a  </b>                      # to commit your work<br>
		<b>git checkout master </b>                 # to return on your master branch<br>
		<b>git merge dev   </b>                     # to merge the branch "dev" into the branch "master"<br>
		<b>git pull mymed master  </b>              # to be sure there are no modification on the main repository<br>
		<b>git push "your public repo" master </b>  # to get your code public<br>
	</p>
	
	<p>
		# Finally send a mail to mymed-develop@lists.gforge.inria.fr to notify all the developers to pull your code
	</p>
	
</div>
