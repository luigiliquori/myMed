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


#include "mymed_JavaIceWrapper.h"
#include <iostream>
#include "p2pConnApi.h"
#include "IceWrapper.hpp"

#include "MessageDispatcher.hpp"
#include "Message.hpp"

IceWrapper iw;
//JNIEnv *callbackEnv;
//jobject callbackJc;
//jobject callbackMid;
//jobject callbackClassref;

extern "C"
JNIEXPORT void JNICALL Java_mymed_JavaIceWrapper_init
  (JNIEnv *env, jobject ob, jstring rsIp, jstring rsPort, jstring userName, jint service) {
    //const jbyte *uname;
    const char *uname = env->GetStringUTFChars(userName, NULL);
    if (uname == NULL) return;
    const char *rIp = env->GetStringUTFChars(rsIp, NULL);
    if (rIp == NULL) return;
    const char *rPt = env->GetStringUTFChars(rsPort, NULL);
    if (rPt == NULL) return;
    std::cerr << "JIW_init: user name is " << uname << std::endl;
    iw.SetName(uname);
    iw.init(uname, service, rIp, rPt);
}

JNIEXPORT void JNICALL Java_mymed_JavaIceWrapper_getConnection
  (JNIEnv *env, jobject ob, jstring otherUser, jint service) {
//    if (iw == NULL) {
//        std::cerr << "error, IceWrapper not initialized" << std::endl;
//        return;
//    }
    const char *oname = env->GetStringUTFChars(otherUser, NULL);
    if (oname == NULL) {
        return;
    }
    std::cerr << "in c: other user name is " << oname << std::endl;
    iw.getConn(oname, service);
}

JNIEXPORT void JNICALL Java_mymed_JavaIceWrapper_send
  (JNIEnv *env, jobject ob, jstring otherUser, jstring message) {
    const char *oname = env->GetStringUTFChars(otherUser, NULL);
    if (oname == NULL) {
        return;
    }
    const char *msg = env->GetStringUTFChars(message, NULL);
    if (msg == NULL) {
        return;
    }
    PseudoTcp::MessageDispatcher *md = iw.getMdOfUser(oname);
    if (md == NULL) {
        std::cerr << "conn for user " << oname << " not found" << std::endl;
        return;
    }
    PseudoTcp::Message *m = new PseudoTcp::Message();
    std::cerr << "filling byteArray" << std::endl;
    std::vector<unsigned char> &ba = m->GetByteArray();
    for (unsigned int i = 0; i < strlen(msg); i++) {
        ba.insert(ba.end(), msg[i]);
    }
    m->SetReqAck(true);
    //cerr << "created message " << m->toString() << endl;
    std::cerr << "about to send msg" << std::endl;
    md->send(m); // todo: does this have to be created on heap?
    std::cerr << "msg sent" << std::endl;
}

JNIEXPORT jstring JNICALL Java_mymed_JavaIceWrapper_getUsersString
  (JNIEnv *env, jobject ob) {
    return env->NewStringUTF(iw.getUsers());
}

JNIEXPORT jint JNICALL Java_mymed_JavaIceWrapper_hasConnection
  (JNIEnv *env, jobject ob, jstring otherUser, jint service) {
    const char *oname = env->GetStringUTFChars(otherUser, NULL);
    if (oname == NULL) {
        return 0;
    }
    std::cerr << "in c: other user name is " << oname << std::endl;
    //iw.getConn(oname, service);
    PseudoTcp::MessageDispatcher *md = iw.getMdOfUser(oname);
    return md != NULL;
}

JNIEXPORT jstring JNICALL Java_mymed_JavaIceWrapper_getInput
  (JNIEnv *env, jobject ob) {
    const char *input = iw.getInput();
    if (input == NULL) return NULL;
    else {
        std::cerr << "JavaIceWrapper_getInput: " << input << std::endl;
        return env->NewStringUTF(input);
    }
}

JNIEXPORT jstring JNICALL Java_mymed_JavaIceWrapper_getInput__Ljava_lang_StringBuffer_2
  (JNIEnv *env, jobject ob, jobject sbFrom){
    char from[200];
    const char *input = iw.getInput(from);
    if (input == NULL) return NULL;
    else {
        if (sbFrom == NULL) return NULL;
        jclass cls = env->GetObjectClass(sbFrom);
        jmethodID mid = env->GetMethodID(cls, "append","(Ljava/lang/String;)Ljava/lang/StringBuffer;");
        if (mid == 0) return NULL;
        jstring jFrom = env->NewStringUTF((const char *) from);
        env->CallObjectMethod(sbFrom, mid, jFrom);
        return env->NewStringUTF(input);
    }
}

//JNIEXPORT void JNICALL Java_mymed_JavaIceWrapper_setIncomingMessageCallback
//  (JNIEnv *env, jobject ob, jobject classref) {
//    callbackEnv = env;
//    callbackClassref = classref;
//    callbackJc = env->GetObjectClass(classref);
//    callbackMid = env->GetMethodID(callbackJc, "incomingMessageCallback", "(Ljava/lang/String;Ljava/lang/String;)V");
//    env->CallObjectMethod(classref, callbackMid, env->NewStringUTF("from"), env->NewStringUTF("msg"));
//}
//
//void invokeCallback(char *from, char *msg) {
//    callbackEnv->CallObjectMethod(callbackClassref, callbackMid, callbackEnv->NewStringUTF(from), callbackEnv->NewStringUTF(msg));
//}






