# Robots

Merge default Drupal robots.txt with Sitemap and custom instructions.

## Configuration

/admin/config/search/robots/settings

## Roadmap

- Create _Robot Instruction_ entities that are holding a reference to content entities
whenever possible, instead of paths, so we can deal with path changes. The other fields
from _Robot Instruction_ can have the _user agent_, _allow_ definition.
Other paths could be references as well (like Views, ...) so we end up with an unified 
solution that prevents manual edition of the robots.txt result file. 
- Provide definition of these entities on the content entity edit form (_user agent_, _allow_).
