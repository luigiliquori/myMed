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
#define WEBAPP_URL    @"http://mymed22.sophia.inria.fr/myRiviera"

#else

#define WEBAPP_URL  @"http://www.mymed.fr/myRiviera"

#endif


#endif
