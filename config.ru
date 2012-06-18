require 'rack-rewrite'

use Rack::Rewrite do
  send_file %r{([^\?]+)}, Dir.getwd + '/data/html/$1', :if => Proc.new { |env|
    path = File.expand_path(Dir.getwd + '/data/html' + env['PATH_INFO'])
    File.file?(path)
  }
  rewrite %r{(.*)}, 'index.php$1'
end

require 'rack-legacy'

use Rack::Legacy::Php, 'data/html'
run Rack::File.new Dir.getwd
