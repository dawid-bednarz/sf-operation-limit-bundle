# INSTALLATION
```composer require dawid-bednarz/sf-operation-limit-bundle```
####1. Create entities file
```php
namespace App\Entity;

use DawBed\PHPOperationLimit\OperationLimit as Base;

class OperationLimit  extends Base
{
}
```
#### 2. Create operation_limit_bundle.yaml in your ~/config/packages directory
```yaml
dawbed_operation_limit_bundle:
    entities:
      Context: 'App\Entity\OperationLimit'
```
#### 3. Register Listener (config/services.yaml)
```yaml
    App\EventListener\OperationLimitListener:
        tags:
            - { name: kernel.event_listener, event: kernel.exception }
```
## Example Uses
```php
       public function sendConfirmationMail(
                  ContextFactoryService $contextFactoryService,
                  OperationLimitService $operationLimitService) 
        {
        $this->operationLimitService->makeByCriteria(
            (new Criteria())
                ->setName($user->getEmail())
                ->setContext($this->contextFactoryService->build(ContextFactoryService::CONFIRMATION))
                ->setAllowed(1)
                ->setOnTime('PT10M')
                ->setForTime('PT30M'));
            
```   
When you try do this again between 10 minutes you get exception
## Example Handler Event
```php
class OperationLimitListener
{
    function __invoke(GetResponseForExceptionEvent $event)
    {
        if (!($event->getException() instanceof ExceedsLimitException)) {
            return;
        }
        $response = new Response();

        $timeToUnlock = $event->getException()->getOperationLimit()->getTimeToUnlock()->format('%Y-%M-%D %H:%I:%S');

        $response->setContent($timeToUnlock);

        $event->setResponse($response);
    }
}
```