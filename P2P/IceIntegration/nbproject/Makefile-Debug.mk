#
# Generated Makefile - do not edit!
#
# Edit the Makefile in the project folder instead (../Makefile). Each target
# has a -pre and a -post target defined where you can add customized code.
#
# This makefile implements configuration specific macros and targets.


# Environment
MKDIR=mkdir
CP=cp
GREP=grep
NM=nm
CCADMIN=CCadmin
RANLIB=ranlib
CC=gcc
CCC=g++
CXX=g++
FC=
AS=as

# Macros
CND_PLATFORM=GNU-Linux-x86
CND_CONF=Debug
CND_DISTDIR=dist

# Include project Makefile
include Makefile

# Object Directory
OBJECTDIR=build/${CND_CONF}/${CND_PLATFORM}

# Object Files
OBJECTFILES= \
	${OBJECTDIR}/IceWrapper.o \
	${OBJECTDIR}/Connection.o \
	${OBJECTDIR}/JavaIceWrapper.o \
	${OBJECTDIR}/main.o \
	${OBJECTDIR}/MovingBuffer.o \
	${OBJECTDIR}/EventLogger.o \
	${OBJECTDIR}/Message.o \
	${OBJECTDIR}/IceConnection.o \
	${OBJECTDIR}/PseudoTcpTx.o \
	${OBJECTDIR}/PseudoTcpRx.o \
	${OBJECTDIR}/PseudoTcpUtil.o \
	${OBJECTDIR}/MessageDispatcher.o


# C Compiler Flags
CFLAGS=

# CC Compiler Flags
CCFLAGS=
CXXFLAGS=

# Fortran Compiler Flags
FFLAGS=

# Assembler Flags
ASFLAGS=

# Link Libraries and Options
LDLIBSOPTIONS=

# Build Targets
.build-conf: ${BUILD_SUBPROJECTS}
	"${MAKE}"  -f nbproject/Makefile-Debug.mk dist/Debug/GNU-Linux-x86/iceintegration

dist/Debug/GNU-Linux-x86/iceintegration: ${OBJECTFILES}
	${MKDIR} -p dist/Debug/GNU-Linux-x86
	${LINK.cc} -o ${CND_DISTDIR}/${CND_CONF}/${CND_PLATFORM}/iceintegration ${OBJECTFILES} ${LDLIBSOPTIONS} 

${OBJECTDIR}/IceWrapper.o: IceWrapper.cpp 
	${MKDIR} -p ${OBJECTDIR}
	${RM} $@.d
	$(COMPILE.cc) -g -I/usr/lib/jvm/java-6-openjdk/include -MMD -MP -MF $@.d -o ${OBJECTDIR}/IceWrapper.o IceWrapper.cpp

${OBJECTDIR}/Connection.o: Connection.cpp 
	${MKDIR} -p ${OBJECTDIR}
	${RM} $@.d
	$(COMPILE.cc) -g -I/usr/lib/jvm/java-6-openjdk/include -MMD -MP -MF $@.d -o ${OBJECTDIR}/Connection.o Connection.cpp

${OBJECTDIR}/JavaIceWrapper.o: JavaIceWrapper.cpp 
	${MKDIR} -p ${OBJECTDIR}
	${RM} $@.d
	$(COMPILE.cc) -g -I/usr/lib/jvm/java-6-openjdk/include -MMD -MP -MF $@.d -o ${OBJECTDIR}/JavaIceWrapper.o JavaIceWrapper.cpp

${OBJECTDIR}/main.o: main.cpp 
	${MKDIR} -p ${OBJECTDIR}
	${RM} $@.d
	$(COMPILE.cc) -g -I/usr/lib/jvm/java-6-openjdk/include -MMD -MP -MF $@.d -o ${OBJECTDIR}/main.o main.cpp

${OBJECTDIR}/MovingBuffer.o: MovingBuffer.cpp 
	${MKDIR} -p ${OBJECTDIR}
	${RM} $@.d
	$(COMPILE.cc) -g -I/usr/lib/jvm/java-6-openjdk/include -MMD -MP -MF $@.d -o ${OBJECTDIR}/MovingBuffer.o MovingBuffer.cpp

${OBJECTDIR}/EventLogger.o: EventLogger.cpp 
	${MKDIR} -p ${OBJECTDIR}
	${RM} $@.d
	$(COMPILE.cc) -g -I/usr/lib/jvm/java-6-openjdk/include -MMD -MP -MF $@.d -o ${OBJECTDIR}/EventLogger.o EventLogger.cpp

${OBJECTDIR}/Message.o: Message.cpp 
	${MKDIR} -p ${OBJECTDIR}
	${RM} $@.d
	$(COMPILE.cc) -g -I/usr/lib/jvm/java-6-openjdk/include -MMD -MP -MF $@.d -o ${OBJECTDIR}/Message.o Message.cpp

${OBJECTDIR}/IceConnection.o: IceConnection.cpp 
	${MKDIR} -p ${OBJECTDIR}
	${RM} $@.d
	$(COMPILE.cc) -g -I/usr/lib/jvm/java-6-openjdk/include -MMD -MP -MF $@.d -o ${OBJECTDIR}/IceConnection.o IceConnection.cpp

${OBJECTDIR}/PseudoTcpTx.o: PseudoTcpTx.cpp 
	${MKDIR} -p ${OBJECTDIR}
	${RM} $@.d
	$(COMPILE.cc) -g -I/usr/lib/jvm/java-6-openjdk/include -MMD -MP -MF $@.d -o ${OBJECTDIR}/PseudoTcpTx.o PseudoTcpTx.cpp

${OBJECTDIR}/PseudoTcpRx.o: PseudoTcpRx.cpp 
	${MKDIR} -p ${OBJECTDIR}
	${RM} $@.d
	$(COMPILE.cc) -g -I/usr/lib/jvm/java-6-openjdk/include -MMD -MP -MF $@.d -o ${OBJECTDIR}/PseudoTcpRx.o PseudoTcpRx.cpp

${OBJECTDIR}/PseudoTcpUtil.o: PseudoTcpUtil.cpp 
	${MKDIR} -p ${OBJECTDIR}
	${RM} $@.d
	$(COMPILE.cc) -g -I/usr/lib/jvm/java-6-openjdk/include -MMD -MP -MF $@.d -o ${OBJECTDIR}/PseudoTcpUtil.o PseudoTcpUtil.cpp

${OBJECTDIR}/MessageDispatcher.o: MessageDispatcher.cpp 
	${MKDIR} -p ${OBJECTDIR}
	${RM} $@.d
	$(COMPILE.cc) -g -I/usr/lib/jvm/java-6-openjdk/include -MMD -MP -MF $@.d -o ${OBJECTDIR}/MessageDispatcher.o MessageDispatcher.cpp

# Subprojects
.build-subprojects:

# Clean Targets
.clean-conf: ${CLEAN_SUBPROJECTS}
	${RM} -r build/Debug
	${RM} dist/Debug/GNU-Linux-x86/iceintegration

# Subprojects
.clean-subprojects:

# Enable dependency checking
.dep.inc: .depcheck-impl

include .dep.inc
