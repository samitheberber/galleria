require 'sinatra/base'

class Galleria < Sinatra::Base
  configure :production, :development do
    enable :logging
  end

  get '/json/get/' do
    # TODO: fixme
    "{data: []}"
  end

end
