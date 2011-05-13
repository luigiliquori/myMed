<?php
class Application
{
	private /*string*/	$name;
	private /*string*/	$description;
	private /*double*/	$grade;
	private /*bool*/	$owned;
	public function __construct(/*string*/ $name, /*double*/ $grade, /*string*/ $description='', /*bool*/ $owned=false)
	{
		$this->name			= $name;
		$this->grade		= $grade;
		$this->description	= $description;
		$this->owned		= $owned;
	}
	public /*string*/ function getName()
	{
		return $this->name;
	}
	public /*string*/ function getDescription()
	{
		return $this->description;
	}
	public /*double*/ function getGrade()
	{
		return $this->grade;
	}
	public /*bool*/ function isOwned()
	{
		return $this->owned;
	}
	public /*string*/ function __toString()
	{
		return '
					<img src="services/'.$this->name.'/icon.png" alt="" />
					<h3><a href="#">'.$this->name.'</a></h3>
					<p>'.$this->description.'</p>
					<div class="grade">
						<div class="cursor" style="width:'.($this->grade*100/5).'%">
							<var>'.$this->grade.'&#160;/&#160;5</var>
						</div>
						<span class="text">Notez&#160;:</span>
						<ul>
							<li>
								<a href="#">
									<span class="text">1&#160;/&#160;5</span>
									<div class="cursor" style="width:20%">
									</div>
								</a>
							</li>
							<li>
								<a href="#">
									<span class="text">2&#160;/&#160;5</span>
									<div class="cursor" style="width:40%">
									</div>
								</a>
							</li>
							<li>
								<a href="#">
									<span class="text">3&#160;/&#160;5</span>
									<div class="cursor" style="width:60%">
									</div>
								</a>
							</li>
							<li>
								<a href="#">
									<span class="text">4&#160;/&#160;5</span>
									<div class="cursor" style="width:80%">
									</div>
								</a>
							</li>
							<li>
								<a href="#">
									<span class="text">5&#160;/&#160;5</span>
									<div class="cursor" style="width:100%">
									</div>
								</a>
							</li>
						</ul>
					</div>';
	}
}