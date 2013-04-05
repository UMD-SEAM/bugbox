grep Target `find ./selenium -maxdepth 1 -type f` | sed -E 's/(.+.py).*"(.+)"/\1 "\2"/g'
