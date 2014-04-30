set :application, "SportChecked"
set :domain,      "sportchecked.cloudapp.net"
set :deploy_to,   "/home/SportChecked"
set :app_path,    "app"

set :user, "sportchecked"
set :use_sudo, false

set :scm,         :git
set :repository,  "file://."
set :deploy_via,  :copy

set :branch,      "master"

# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web,        'sportchecked.cloudapp.net'                         # Your HTTP server, Apache/etc
role :app,        'sportchecked.cloudapp.net', :primary => true       # This may be the same as your `Web` server

set  :keep_releases,  3

set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", web_path + "/uploads", "vendor"]

set :use_composer, true
set :update_vendors, true

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL
