

TARGET_DIR = bugbox_1.0/usr/lib/bugbox
SOURCE_DIR = ..
FILE_FILTER = ! -iname '*.pyc' -and ! -iname '.git*'

package:
	mkdir -p $(TARGET_DIR)
	mkdir $(TARGET_DIR)/live_systems
	mkdir $(TARGET_DIR)/traces
	mkdir $(TARGET_DIR)/docs	
	find $(SOURCE_DIR) -maxdepth 1 -type f  $(FILE_FILTER) -exec cp -t $(TARGET_DIR) {} \;
	(cd $(SOURCE_DIR) && find ./framework -type f  $(FILE_FILTER) ! -path '*/chroot_envs/*' ! -path '*/metasploit-framework/*' -exec cp -p --parents {} dist/chroot \;)
	(cd $(SOURCE_DIR) && find usr -type f | xargs md5sum) > $(TARGET_DIR)/DEBIAN/md5sums
	
	ls -la chroot

clean:
	@echo "Make clean"
	rm -rf chroot
	rm -rf deb
	mkdir -p chroot
	mkdir -p deb


