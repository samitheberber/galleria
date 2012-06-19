require 'sinatra/base'

class Galleria < Sinatra::Base
  configure :production, :development do
    enable :logging
  end
end
