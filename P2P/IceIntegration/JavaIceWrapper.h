/*
 * Copyright 2012 POLITO 
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/
/* DO NOT EDIT THIS FILE - it is machine generated */
#include </usr/lib/jvm/java-6-openjdk/include/jni.h>
/* Header for class JavaIceWrapper */

#ifndef _Included_JavaIceWrapper
#define _Included_JavaIceWrapper
#ifdef __cplusplus
extern "C" {
#endif
/*
 * Class:     JavaIceWrapper
 * Method:    init
 * Signature: (Ljava/lang/String;Ljava/lang/String;Ljava/lang/String;I)V
 */
JNIEXPORT void JNICALL Java_mymed_JavaIceWrapper_init
  (JNIEnv *, jobject, jstring, jstring, jstring, jint);

/*
 * Class:     JavaIceWrapper
 * Method:    getConnection
 * Signature: (Ljava/lang/String;I)V
 */
JNIEXPORT void JNICALL Java_mymed_JavaIceWrapper_getConnection
  (JNIEnv *, jobject, jstring, jint);

/*
 * Class:     JavaIceWrapper
 * Method:    send
 * Signature: (Ljava/lang/String;Ljava/lang/String;)V
 */
JNIEXPORT void JNICALL Java_mymed_JavaIceWrapper_send
  (JNIEnv *, jobject, jstring, jstring);

/*
 * Class:     JavaIceWrapper
 * Method:    getUsersString
 * Signature: ()Ljava/lang/String;
 */
JNIEXPORT jstring JNICALL Java_mymed_JavaIceWrapper_getUsersString
  (JNIEnv *, jobject);

#ifdef __cplusplus
}
#endif
#endif
