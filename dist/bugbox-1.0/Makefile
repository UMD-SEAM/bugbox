TARGET_ROOT = data
TARGET_DIR = $(TARGET_ROOT)
SOURCE_DIR = ../..
PACKAGE_NAME = bugbox-1.0
FILE_FILTER = ! -iname '*.pyc' -and ! -iname '.git*'

BINDIR = $(DESTDIR)/usr/lib/bugbox

export DEBEMAIL=gnilson@umd.edu
export DEBFULLNAME="Gary Nilson"

build:
	echo "BUILD.. doing"

package:
	echo "Make Package"
	mkdir -p $(TARGET_DIR)
	mkdir $(TARGET_DIR)/live_systems
	mkdir $(TARGET_DIR)/traces
	mkdir $(TARGET_DIR)/docs	
	find $(SOURCE_DIR) -maxdepth 1 -type f  $(FILE_FILTER) -exec cp -t $(TARGET_DIR) {} \;
	@echo "Copying framework..."
	(cd $(SOURCE_DIR) && cp -R ./lib dist/bugbox-1.0/$(TARGET_DIR))
	(cd $(SOURCE_DIR) && find ./framework -type f  $(FILE_FILTER) ! -path '*/Debian?/*' ! -path '*/metasploit-framework/*' -exec cp -p --parents {} dist/bugbox-1.0/$(TARGET_DIR) \;)

	@echo "Building deb"
	debuild
	@echo "Done building package"	

install:
	@echo "INSTALLING MOFO"
	mkdir -p $(BINDIR)
	cp -R data/* $(BINDIR)

uninstall:
	@echo "UNINSTALL"
	rm -rf $(BINDIR)

clean:
	@echo "Make clean"
	rm -rf $(BINDIR)

packageclean:
	rm -rf data/*
	rm -rf debian/bugbox

.PHONY: package install uninstall clean

#(cd $(SOURCE_DIR) && find usr -type f | xargs md5sum) > $(TARGET_DIR)/DEBIAN/md5sums
#(cd .. && dpkg-deb -b $(PACKAGE_NAME))
