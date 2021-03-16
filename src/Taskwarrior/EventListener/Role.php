<?php

namespace hxv\TaskRofi\Taskwarrior\EventListener;

class Role
{
    public const MENU = 'taskwarrior';

    public const TASK = 'taskwarrior.task';

    public const TASK_OPEN = 'taskwarrior.task.open';

    public const TASK_CLOSE = 'taskwarrior.task.close';

    public const TASK_DELETE = 'taskwarrior.task.delete';

    public const TASK_RENAME = 'taskwarrior.task.rename';

    public const TASK_START = 'taskwarrior.task.start';

    public const TASK_STOP = 'taskwarrior.task.stop';

    public const ADD_TASK = 'taskwarrior.add_task';

    public const TASK_TAGS = 'taskwarrior.task.tags';

    public const TASK_TAG = 'taskwarrior.task.tag';

    public const TASK_TAGS_ADD = 'taskwarrior.task.tags.add';

    public const TASK_TAG_REMOVE = 'taskwarrior.task.tag.remove';
}
