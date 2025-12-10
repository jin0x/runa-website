#!/bin/bash

# Clear and rebuild Acorn/Blade view cache
wp acorn optimize:clear
wp acorn view:cache

# Flush WordPress object cache
wp cache flush
