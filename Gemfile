source "https://rubygems.org"

# Jekyll itself, so `bundle exec jekyll serve` works. GitHub Pages pins its own
# version during the build and ignores this constraint.
gem "jekyll", "~> 3.9"

# kramdown's GitHub-Flavored-Markdown parser. This is the same parser GitHub
# Pages uses, and the Zlog posts are written in GFM.
gem "kramdown-parser-gfm", "~> 1.1"

# Local preview only. `jekyll serve` needs jekyll-watch to rebuild as you
# edit; GitHub Pages never watches, so nothing here is required in production.
# If you would rather not install it, serve without live reload using
# `bundle exec jekyll serve --no-watch`.
group :development do
  gem "jekyll-watch", "~> 2.2"
end
