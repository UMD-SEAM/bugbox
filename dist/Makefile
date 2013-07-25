


TARGET_DIR = bugbox_1.0
SOURCE_DIR = ..
FILE_FILTER = ! -iname '*.pyc' -and ! -iname '.git*'

package:
	mkdir -p $(TARGET_DIR)
	mkdir -p deb
	mkdir $(TARGET_DIR)/live_systems
	mkdir $(TARGET_DIR)/traces
	mkdir $(TARGET_DIR)/docs	
	find $(SOURCE_DIR) -maxdepth 1 -type f  $(FILE_FILTER) -exec cp -t $(TARGET_DIR) {} \;
	# find $(SOURCE_DIR)/framework -type f  $(FILE_FILTER) ! -path '*/chroot_envs/*' -exec cp --parents {} $(TARGET_DIR)`echo {} | sed 's/\.\.\//\//'` \;
	(cd $(SOURCE_DIR) && find ./framework -type f  $(FILE_FILTER) ! -path '*/chroot_envs/*' ! -path '*/metasploit-framework/*' -exec cp -p --parents {} dist/chroot \;)
	ls -la chroot

clean:
	@echo "Make clean"
	rm -rf chroot
	rm -rf deb
	mkdir -p chroot
	mkdir -p deb


