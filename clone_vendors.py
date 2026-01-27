import os
import subprocess
import json
import glob

PACKAGES = [
    {"name": "diglactic/laravel-breadcrumbs", "url": "https://github.com/diglactic/laravel-breadcrumbs.git"},
    {"name": "spatie/laravel-activitylog", "url": "https://github.com/spatie/laravel-activitylog.git"},
    {"name": "proengsoft/laravel-jsvalidation", "url": "https://github.com/proengsoft/laravel-jsvalidation.git"},
    {"name": "propaganistas/laravel-phone", "url": "https://github.com/Propaganistas/Laravel-Phone.git"},
    {"name": "laravel-notification-channels/authy", "url": "https://github.com/laravel-notification-channels/authy.git"},
    {"name": "mcamara/laravel-localization", "url": "https://github.com/mcamara/laravel-localization.git"},
    {"name": "mariuzzo/laravel-js-localization", "url": "https://github.com/rmariuzzo/Laravel-JS-Localization.git"},
    {"name": "appstract/laravel-opcache", "url": "https://github.com/appstract/laravel-opcache.git"},
    {"name": "torann/geoip", "url": "https://github.com/Torann/laravel-geoip.git"},
    {"name": "vinkla/hashids", "url": "https://github.com/vinkla/laravel-hashids.git"},
    {"name": "barryvdh/laravel-snappy", "url": "https://github.com/barryvdh/laravel-snappy.git"},
    {"name": "maatwebsite/excel", "url": "https://github.com/Maatwebsite/Laravel-Excel.git"},
    {"name": "yajra/laravel-datatables-buttons", "url": "https://github.com/yajra/laravel-datatables-buttons.git"},
    {"name": "yajra/laravel-datatables-html", "url": "https://github.com/yajra/laravel-datatables-html.git"},
    {"name": "yajra/laravel-datatables-fractal", "url": "https://github.com/yajra/laravel-datatables-fractal.git"},
    {"name": "yajra/laravel-datatables-oracle", "url": "https://github.com/yajra/laravel-datatables.git"},
    {"name": "spatie/laravel-db-snapshots", "url": "https://github.com/spatie/laravel-db-snapshots.git"},
    {"name": "spatie/laravel-ignition", "url": "https://github.com/spatie/laravel-ignition.git"},
    {"name": "barryvdh/laravel-debugbar", "url": "https://github.com/barryvdh/laravel-debugbar.git"},
    {"name": "barryvdh/laravel-ide-helper", "url": "https://github.com/barryvdh/laravel-ide-helper.git"},
    {"name": "beyondcode/laravel-dump-server", "url": "https://github.com/beyondcode/laravel-dump-server.git"},
    {"name": "beyondcode/laravel-query-detector", "url": "https://github.com/beyondcode/laravel-query-detector.git"},
    {"name": "itsgoingd/clockwork", "url": "https://github.com/itsgoingd/clockwork.git"}
]

os.makedirs("packages", exist_ok=True)

def patch_composer_json(path):
    if not os.path.exists(path):
        return
    
    with open(path, 'r') as f:
        try:
            data = json.load(f)
        except:
            return

    changed = False
    
    # Enforce dev stability
    if data.get('minimum-stability') != 'dev':
        data['minimum-stability'] = 'dev'
        changed = True
        
    for section in ['require', 'require-dev']:
        if section not in data:
            continue
            
        reqs = data[section]
        keys_to_del = []
        needs_framework = False
        
        for k in list(reqs.keys()):
            if k.startswith('illuminate/'):
                keys_to_del.append(k)
                needs_framework = True
        
        for k in keys_to_del:
            del reqs[k]
            changed = True
            
        if needs_framework:
             if section == 'require': # Only add framework to require main
                 reqs['laravel/framework'] = '^12.0'
                 changed = True

        # Ensure we don't conflict with ourselves
        if 'laravel/framework' in reqs:
            reqs['laravel/framework'] = '^12.0'
            changed = True
            
    if changed:
        print(f"Patching {path}")
        with open(path, 'w') as f:
            json.dump(data, f, indent=4)

for pkg in PACKAGES:
    name = pkg['name']
    url = pkg['url']
    vendor, package_name = name.split('/')
    
    target_dir = f"packages/{vendor}/{package_name}"
    
    if os.path.exists(target_dir):
        print(f"Skipping {name}, exists")
    else:
        print(f"Cloning {name}...")
        subprocess.run(["git", "clone", url, target_dir], check=False)
        
    # Patch composer.json
    patch_composer_json(f"{target_dir}/composer.json")
    
# Update root composer.json with paths
with open("composer.json", "r") as f:
    root_data = json.load(f)

repos = root_data.get("repositories", [])
existing_urls = [r.get("url") for r in repos]

for pkg in PACKAGES:
    vendor, package_name = pkg['name'].split('/')
    path_url = f"packages/{vendor}/{package_name}"
    
    # Check if we should use wildcard path or specific
    # Using specific path is safer for detection
    
    if path_url not in existing_urls:
         # Check if we can group by vendor wildcard
         wildcard = f"packages/{vendor}/*"
         if wildcard not in existing_urls:
             repos.append({"type": "path", "url": wildcard})
             existing_urls.append(wildcard)

root_data['repositories'] = repos
root_data['minimum-stability'] = 'dev'
root_data['prefer-stable'] = True

with open("composer.json", "w") as f:
    json.dump(root_data, f, indent=4)

print("Done cloning and patching.")
