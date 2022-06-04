<?php


namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Routing\Controller as BaseController;

class MenuController extends BaseController
{
    /*
    Requirements:
    - the eloquent expressions should result in EXACTLY one SQL query no matter the nesting level or the amount of menu items.
    - it should work for infinite level of depth (children of childrens children of childrens children, ...)
    - verify your solution with `php artisan test`
    - do a `git commit && git push` after you are done or when the time limit is over

    Hints:
    - open the `app/Http/Controllers/MenuController` file
    - eager loading cannot load deeply nested relationships
    - a recursive function in php is needed to structure the query results
    - partial or not working answers also get graded so make sure you commit what you have


    Sample response on GET /menu:
    ```json
    [
        {
            "id": 1,
            "name": "All events",
            "url": "/events",
            "parent_id": null,
            "created_at": "2021-04-27T15:35:15.000000Z",
            "updated_at": "2021-04-27T15:35:15.000000Z",
            "children": [
                {
                    "id": 2,
                    "name": "Laracon",
                    "url": "/events/laracon",
                    "parent_id": 1,
                    "created_at": "2021-04-27T15:35:15.000000Z",
                    "updated_at": "2021-04-27T15:35:15.000000Z",
                    "children": [
                        {
                            "id": 3,
                            "name": "Illuminate your knowledge of the laravel code base",
                            "url": "/events/laracon/workshops/illuminate",
                            "parent_id": 2,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        },
                        {
                            "id": 4,
                            "name": "The new Eloquent - load more with less",
                            "url": "/events/laracon/workshops/eloquent",
                            "parent_id": 2,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        }
                    ]
                },
                {
                    "id": 5,
                    "name": "Reactcon",
                    "url": "/events/reactcon",
                    "parent_id": 1,
                    "created_at": "2021-04-27T15:35:15.000000Z",
                    "updated_at": "2021-04-27T15:35:15.000000Z",
                    "children": [
                        {
                            "id": 6,
                            "name": "#NoClass pure functional programming",
                            "url": "/events/reactcon/workshops/noclass",
                            "parent_id": 5,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        },
                        {
                            "id": 7,
                            "name": "Navigating the function jungle",
                            "url": "/events/reactcon/workshops/jungle",
                            "parent_id": 5,
                            "created_at": "2021-04-27T15:35:15.000000Z",
                            "updated_at": "2021-04-27T15:35:15.000000Z",
                            "children": []
                        }
                    ]
                }
            ]
        }
    ]
     */

    public function getMenuItems() {
        try{

            $menuitemsQuery = MenuItem::query();
            $menuitems = $menuitemsQuery->get()->toArray();
            //$menuitemsQuery->with('childiren');
            //$menuitems = $menuitemsQuery->whereNull('parent_id')->get();


            $events = array(
                'events' => array(),
                'parent_events' => array()
            );

            foreach ($menuitems as $menuitem){
                $events['events'][$menuitem['id']] = $menuitem;
                $events['parent_events'][$menuitem['parent_id']][] = $menuitem['id'];
            }


            $allmenuitems = $this->buildCategory(NULL, $events);
            echo $allmenuitems;exit;


        } catch (Exception $exception){
            echo $exception->getMessage();exit;
        }
    }



    function buildCategory($parent, $events) {
        $html = "";
        if (isset($events['parent_events'][$parent])) {
            $html .= "<ul>\n";
            foreach ($events['parent_events'][$parent] as $event_id) {
                if (!isset($events['parent_events'][$event_id])) {
                    $html .= "<li>\n " . $events['events'][$event_id]['name'] . "\n</li> \n";
                }
                if (isset($events['parent_events'][$event_id])) {
                    $html .= "<li>\n " . $events['events'][$event_id]['name'] . " \n";
                    $html .= $this->buildCategory($event_id, $events);
                    $html .= "</li> \n";
                }
            }
            $html .= "</ul> \n";
        }
        return $html;
    }
}
