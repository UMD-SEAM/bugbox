#!/bin/sh

# $Id: wsprovide.sh 2361 2007-02-14 14:52:02Z jason.greene@jboss.com $

DIRNAME=`dirname $0`
PROGNAME=`basename $0`

# OS specific support (must be 'true' or 'false').
cygwin=false;
case "`uname`" in
    CYGWIN*)
        cygwin=true
        ;;
esac

# For Cygwin, ensure paths are in UNIX format before anything is touched
if $cygwin ; then
    [ -n "$JBOSS_HOME" ] &&
        JBOSS_HOME=`cygpath --unix "$JBOSS_HOME"`
    [ -n "$JAVA_HOME" ] &&
        JAVA_HOME=`cygpath --unix "$JAVA_HOME"`
fi

# Setup JBOSS_HOME
if [ "x$JBOSS_HOME" = "x" ]; then
    # get the full path (without any relative bits)
    JBOSS_HOME=`cd $DIRNAME/..; pwd`
fi
export JBOSS_HOME

# Setup the JVM
if [ "x$JAVA" = "x" ]; then
    if [ "x$JAVA_HOME" != "x" ]; then
	JAVA="$JAVA_HOME/bin/java"
    else
	JAVA="java"
    fi
fi

#JPDA options. Uncomment and modify as appropriate to enable remote debugging .
#JAVA_OPTS="-classic -Xdebug -Xnoagent -Djava.compiler=NONE -Xrunjdwp:transport=dt_socket,address=8787,server=y,suspend=n $JAVA_OPTS"

# Setup JBoss sepecific properties
JAVA_OPTS="$JAVA_OPTS"

# Setup the java endorsed dirs
JBOSS_ENDORSED_DIRS="$JBOSS_HOME/lib/endorsed"

# Setup the wstools classpath
WSPROVIDE_CLASSPATH="$WSPROVIDE_CLASSPATH:$JBOSS_HOME/client/jboss-xml-binding.jar"
WSPROVIDE_CLASSPATH="$WSPROVIDE_CLASSPATH:$JBOSS_HOME/client/activation.jar"
WSPROVIDE_CLASSPATH="$WSPROVIDE_CLASSPATH:$JBOSS_HOME/client/getopt.jar"
WSPROVIDE_CLASSPATH="$WSPROVIDE_CLASSPATH:$JBOSS_HOME/client/javassist.jar"
WSPROVIDE_CLASSPATH="$WSPROVIDE_CLASSPATH:$JBOSS_HOME/client/jaxb-api.jar"
WSPROVIDE_CLASSPATH="$WSPROVIDE_CLASSPATH:$JBOSS_HOME/client/jaxb-impl.jar"
WSPROVIDE_CLASSPATH="$WSPROVIDE_CLASSPATH:$JBOSS_HOME/client/jbossall-client.jar"
WSPROVIDE_CLASSPATH="$WSPROVIDE_CLASSPATH:$JBOSS_HOME/client/jbossws-client.jar"
WSPROVIDE_CLASSPATH="$WSPROVIDE_CLASSPATH:$JBOSS_HOME/client/jboss-jaxws.jar"
WSPROVIDE_CLASSPATH="$WSPROVIDE_CLASSPATH:$JBOSS_HOME/client/jboss-jaxrpc.jar"
WSPROVIDE_CLASSPATH="$WSPROVIDE_CLASSPATH:$JBOSS_HOME/client/jboss-saaj.jar"
WSPROVIDE_CLASSPATH="$WSPROVIDE_CLASSPATH:$JBOSS_HOME/client/log4j.jar"
WSPROVIDE_CLASSPATH="$WSPROVIDE_CLASSPATH:$JBOSS_HOME/client/mail.jar"

# For Cygwin, switch paths to Windows format before running java
if $cygwin; then
    JBOSS_HOME=`cygpath --path --windows "$JBOSS_HOME"`
    JAVA_HOME=`cygpath --path --windows "$JAVA_HOME"`
    WSPROVIDE_CLASSPATH=`cygpath --path --windows "$WSPROVIDE_CLASSPATH"`
    JBOSS_ENDORSED_DIRS=`cygpath --path --windows "$JBOSS_ENDORSED_DIRS"`
fi

# Execute the JVM
"$JAVA" $JAVA_OPTS \
   -Djava.endorsed.dirs="$JBOSS_ENDORSED_DIRS" \
   -Dlog4j.configuration=wstools-log4j.xml \
   -classpath "$WSPROVIDE_CLASSPATH" \
   org.jboss.ws.tools.jaxws.command.wsprovide "$@"
