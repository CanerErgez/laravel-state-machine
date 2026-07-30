<?php

namespace Caner\StateMachine\Console\Commands;

use Caner\StateMachine\Concerns\BaseStateMachine;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

final class StateMachineDiagramCommand extends Command
{
    protected $signature = 'state-machine:diagram
        {machine : Fully-qualified state machine class}
        {model : Fully-qualified Eloquent model class}
        {id : Model primary key}
        {attribute : State attribute}
        {--output= : Write Mermaid markup to this path}';

    protected $description = 'Generate a Mermaid diagram for a state machine';

    public function handle(): int
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = $this->argument('model');
        $model = $modelClass::query()->findOrFail($this->argument('id'));
        /** @var BaseStateMachine $machine */
        $machine = app()->make($this->argument('machine'), [
            'model' => $model,
            'mainAttribute' => $this->argument('attribute'),
        ]);

        $lines = ['stateDiagram-v2'];
        foreach ($machine->transitions() as $from => $targets) {
            foreach ($targets as $to => $transition) {
                $lines[] = sprintf(
                    '    %s --> %s: %s',
                    class_basename($from),
                    class_basename($to),
                    class_basename($transition),
                );
            }
        }
        $diagram = implode(PHP_EOL, $lines).PHP_EOL;

        if ($output = $this->option('output')) {
            File::ensureDirectoryExists(dirname($output));
            File::put($output, $diagram);
            $this->info("Diagram written to {$output}");
        } else {
            $this->line($diagram);
        }

        return self::SUCCESS;
    }
}
