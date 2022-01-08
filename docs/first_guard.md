


# Create First Guard
Guards are control mechanism of state changes.

Notes: 
- You can add an additional data for next steps if you want.

Example;

    $this->data['data']['additional_data'] = 'Additional Data';

- You can use this data to next Guards, Actions or AfterActions. So,
  returning useful values with this `$this->data['data']` would make sense for later use.
  
Later Usage;

    $eaxmple = $this->data['additional_data'];

**İmportant!!!**
-  Each Guard should be have `$result->data['result']` value.
Because each guard after the run check this value. When this value are `false`
stop the Transition and throw an exception.
   
Example;

    if($this->exampleMethod()) {
        /** You can add any data, like an `error` */
        $this->data['data']['error'] = 'An Error Occured';
        /** or, like an `any` */
        $this->data['data']['any'] = 'An Error Occured';
        
        $this->data['result'] = false;
    } else {
        $this->data['result'] = true;
    }

    return $this;
- If you are use `$this->data['data']['error']` value,
when code find any `false` in `$this->data['result']`,
this error message add the throwing exception.  
  
- Each Guard must be return `$this`

Example Guard;

In `app\Services\PostStateMachine\Guards\ExampleGuard.php`;

    namespace App\Services\PostStateMachine\Guards;

    use Caner\StateMachine\Concerns\BaseGuard;

    class ExampleGuard extends BaseGuard
    {
        public function check(): BaseGuard
        {
            $this->data['result'] = $this->baseStateMachine->getModel()->status === 1;
    
            return $this;
        }
    }
