#!/usr/bin/env python

"""PNG image shrinking script backed by TinyPNG.com

Images are uploaded to their service and smaller versions are downloaded
in place. Note: You need to install their `tinify' package and have an
API key for this to work.
"""

from __future__ import print_function

import fnmatch
import os
import tinify
from threading import Thread
from queue import Queue, Empty

def side_file(name):
    return os.path.join(os.path.dirname(os.path.abspath(__file__)), name)

IMAGES_ROOT = side_file('images')
IMAGE_DIRS = ('content', 'template')

KEY_FILE_NAME = '.tinify-key'
KEY_FILE = side_file(KEY_FILE_NAME)

SHRUNK_FILE_NAME = '.shrunk-images'
SHRUNK_FILES = None

def threadpool(action, datas):
    if not datas:
        return

    # Use threading to do some at the same time
    q = Queue()
    for data in datas:
        q.put(data)

    def worker():
        while True:
            try:
                data = q.get_nowait()
            except Empty:
                return
            else:
                action(data)
                q.task_done()

    # Build our pool
    threads = []
    for n in range(4):
        t = Thread(target=worker, name='worker-'+str(n))
        threads.append(t)
        t.start()

    # Wait for completeion
    q.join()

    # Tidy our threads
    for t in threads:
        t.join()

def ensure_key():
    if not os.path.exists(KEY_FILE):
        exit("You need to get an API key from https://tinypng.com/ and " \
           + "put it in '{0}' next to this script.".format(KEY_FILE_NAME))

    with open(KEY_FILE, 'r') as kf:
        tinify.key = kf.read().strip()

    try:
        tinify.validate()
    except tinify.Error:
        # Validation of API key failed.
        exit("Your API Key from '{0}' was rejected.".format(KEY_FILE))

def recursive_glob(root, pattern):
    # recursive glob not available in Python 2
    # via https://stackoverflow.com/a/2186565/67873
    matches = []
    for curr_dir, _, filenames in os.walk(root):
        for filename in fnmatch.filter(filenames, pattern):
            matches.append(os.path.join(curr_dir, filename))

    return matches

def find_all_pngs():
    all_pngs = []
    for dir_ in IMAGE_DIRS:
        all_pngs += recursive_glob(dir_, '*.png')
    return all_pngs

def has_been_shrunk(file_path):
    global SHRUNK_FILES
    if SHRUNK_FILES is None:
        if not os.path.exists(SHRUNK_FILE_NAME):
            SHRUNK_FILES = []
        else:
            with open(SHRUNK_FILE_NAME, 'r') as sf:
                SHRUNK_FILES = [l.strip() for l in sf.readlines()]

    return file_path in SHRUNK_FILES

def find_pngs_to_shrink():
    os.chdir(IMAGES_ROOT)

    all_pngs = find_all_pngs()
    if not all_pngs:
        exit("Didn't find any png images!")

    to_shrink = [png_path for png_path in all_pngs
                 if not has_been_shrunk(png_path)]
    return to_shrink

def shrink(file_path):
    tinify.from_file(file_path).to_file(file_path)
    pass

def convert_pngs(to_shrink):
    global SHRUNK_FILES

    assert to_shrink, "No images to process!"

    threadpool(shrink, to_shrink)

    SHRUNK_FILES += to_shrink
    SHRUNK_FILES.sort()

    with open(SHRUNK_FILE_NAME, 'w') as sf:
        print("\n".join(SHRUNK_FILES), file=sf)

def total_size(paths):
    total = sum(os.path.getsize(path) for path in paths)
    return total

def sizeof_fmt(num, suffix='B'):
    # Via https://stackoverflow.com/a/1094933/67873
    for unit in ('','Ki','Mi','Gi','Ti','Pi','Ei','Zi'):
        if abs(num) < 1024.0:
            return "%3.1f%s%s" % (num, unit, suffix)
        num /= 1024.0
    return "%.1f%s%s" % (num, 'Yi', suffix)

def print_change(orig_size, new_size):
    difference = orig_size - new_size
    assert difference > 0, "Files became larger!"

    pc_diff = difference * 100.0 / orig_size
    abs_diff = sizeof_fmt(difference)

    print("Files shrunk by {0:.1f}% ({1}).".format(pc_diff, abs_diff))

if __name__ == '__main__':
    to_shrink = find_pngs_to_shrink()

    orig_size = total_size(to_shrink)

    if not to_shrink:
        exit("No images needed shrinking")
    else:
        print("Found {} images to shrink".format(len(to_shrink)))

    ensure_key()
    convert_pngs(to_shrink)

    print_change(orig_size, total_size(to_shrink))
