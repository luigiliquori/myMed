//
//  conf.h
//  myEurope
//
//  Created by Emilio on 26/12/12.
//  Copyright (c) 2012 myMed. All rights reserved.
//

#ifndef myEurope_conf_h
#define myEurope_conf_h

//#define TESTING 1

#if defined(TESTING)

#warning Testing enabled
#define WEBAPP_URL    @"http://mymed22.sophia.inria.fr/myEurope"

#else

#define WEBAPP_URL  @"http://www.mymed.fr/myEurope"

#endif


#define APPNAME @"myEurope"

#define DEFKEY_USERNAME @"kusr"
#define DEFKEY_PWD @"kpwd"

#define MYMED_BACKEND_URL   @"http://www.mymed.fr:8080/backend"

typedef enum {
    SERVER_CODE_CREATE = 0,
    SERVER_CODE_READ   = 1,
    SERVER_CODE_UPDATE = 2,
    SERVER_CODE_DELETE = 3
} ServerAPICodes;

#endif
