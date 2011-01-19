package com.mymed.controller.core.services.requesthandler;

public interface IRequestHandler {
	static int CONNECT = 0;
	static int SETKEYSPACE = 1;
	static int SETCOLUMNFAMILY = 2;
	static int SETKEYUSERID = 3;
	static int INSERTKEY = 4;
	static int GETKEY = 5;
}
