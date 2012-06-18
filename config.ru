require 'rack-rewrite'
require './lib/galleria'

use Rack::Rewrite do
  send_file %r{([^\?]+)}, Dir.getwd + '/public/$1', :if => Proc.new { |env|
    path = File.expand_path(Dir.getwd + '/public' + env['PATH_INFO'])
    File.file?(path)
  }
  rewrite %r{(.*)}, 'index.php$1'
end

require 'rack-legacy'

use Rack::Legacy::Php, 'data/html'
run Galleria.new
