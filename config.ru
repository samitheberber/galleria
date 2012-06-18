$LOAD_PATH << './lib'

require 'rack-rewrite'

use Rack::Rewrite do
  send_file %r{([^\?]+)}, Dir.getwd + '/public/$1', :if => Proc.new { |env|
    path = File.expand_path(Dir.getwd + '/public' + env['PATH_INFO'])
    File.file?(path)
  }
  rewrite '/', 'index.php'
  [
    'browse',
    'search',
    'help',
    'login',
  ].each do |page|
    rewrite %r{/#{page}/?}, "index.php/#{page}"
  end
end

require 'rack-legacy'
use Rack::Legacy::Php, 'data/html'

require 'galleria'
run Galleria
