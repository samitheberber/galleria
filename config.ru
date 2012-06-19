$LOAD_PATH << './lib'

require 'rack-rewrite'

use Rack::Rewrite do
  send_file %r{([^\?]+)}, Dir.getwd + '/public/$1', :if => Proc.new { |env|
    path = File.expand_path(Dir.getwd + '/public' + env['PATH_INFO'])
    File.file?(path)
  }
  rewrite '/', 'index.php'
  rewrite %r{/images/\?id(.*)}, 'index.php/images/\?id$1'
  rewrite %r{/images/file/\?id(.*)}, 'index.php/images/file/\?id$1'
  rewrite %r{/json/post/}, 'index.php/json/post/'
  rewrite %r{/json/get/\?(.*)}, 'index.php/json/get/\?$1'
  [
    'browse',
    'search',
    'help',
    'login',
    'admin/usercontrol',
    'admin/picturecontrol',
    'admin',
    'profile',
  ].each do |page|
    rewrite %r{/#{page}/?}, "index.php/#{page}"
  end
end

require 'rack-legacy'
use Rack::Legacy::Php, 'data/html'

require 'galleria'
run Galleria
