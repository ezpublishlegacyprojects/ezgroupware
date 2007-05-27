#!/bin/bash
# cd trunk
exec ctags-exuberant \
-h ".php" -R \
--exclude="\.svn" \
--totals=yes \
--tag-relative=yes \
--PHP-kinds=+cf \
--regex-PHP='/abstract class ([^ ]*)/\1/c/' \
--regex-PHP='/interface ([^ ]*)/\1/c/' \
--regex-PHP='/(public |static |abstract |protected |private )+function ([^ (]*)/\2/f/'

# Found here: http://weierophinney.net/matthew/archives/134-exuberant-ctags-with-PHP-in-Vim.html

# to use the tags in VIM: :set tags=tags //depending on where you saved it
# CTRL-] Jumps to the definition of a tag
# CTRL-T takes you back from where you came
# CTRL-W ] Jumps to the definition of a tag in a splitted windows

