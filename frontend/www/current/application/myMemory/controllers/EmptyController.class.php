<?php

/**
 *  Default controller that does nothing :
 *  To be inherited by controllers how do not need authentication
 */
class EmptyController extends AbstractController {
	public /*String*/ function handleRequest() {
		// Nothing !!
	}
}