require 'sinatra/base'

class Galleria < Sinatra::Base

  set :public_folder, Dir.getwd + '/public'

  configure :production, :development do
    enable :logging
  end
end
