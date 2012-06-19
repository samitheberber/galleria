$LOAD_PATH << './lib'

require 'rack-rewrite'

use Rack::Rewrite do
  rewrite '/', 'index.php'
  rewrite %r{/images/\?id(.*)}, 'index.php/images/\?id$1'
  rewrite %r{/images/file/\?(.*)}, 'index.php/images/file/\?$1'
  rewrite %r{/json/post/}, 'index.php/json/post/'
  rewrite %r{/json/get/\?(.*)}, 'index.php/json/get/\?$1'
  rewrite %r{/browse/categoryedit/\?(.*)}, 'index.php/browse/categoryedit/\?$1'
  rewrite %r{/browse/\?(.*)}, 'index.php/browse/\?$1'
  rewrite %r{/tag/\?(.*)}, 'index.php/tag/\?$1'
  [
    'browse',
    'search',
    'help',
    'login/out',
    'login',
    'admin/usercontrol',
    'admin/picturecontrol',
    'admin',
    'profile',
  ].each do |page|
    rewrite %r{^/#{page}/?}, "index.php/#{page}"
  end
end

require 'rack-legacy'
use Rack::Legacy::Php, 'data/html'

require 'galleria'
run Galleria
