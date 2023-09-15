<?php

return [
    'common' => [
        'actions' => 'Actions',
        'create' => 'Create',
        'edit' => 'Edit',
        'update' => 'Update',
        'new' => 'New',
        'cancel' => 'Cancel',
        'attach' => 'Attach',
        'detach' => 'Detach',
        'save' => 'Save',
        'delete' => 'Delete',
        'delete_selected' => 'Delete selected',
        'search' => 'Search...',
        'back' => 'Back to Index',
        'are_you_sure' => 'Are you sure?',
        'no_items_found' => 'No items found',
        'created' => 'Successfully created',
        'updated' => 'Successfully updated',
        'saved' => 'Saved successfully',
        'removed' => 'Successfully removed',
        'assigned' => 'Successfully assigned',
        'revoked' => 'Successfully revoked',
        'approved' => 'Successfully approved',
        'commented' => 'Successfully commented',
        'disapproved' => 'Successfully disapproved',
        'replied' => 'Successfully replied',
    ],

    'users' => [
        'name' => 'Users',
        'index_title' => 'Users List',
        'new_title' => 'New User',
        'create_title' => 'Create User',
        'edit_title' => 'Edit User',
        'show_title' => 'Show User',
        'inputs' => [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
        ],
    ],

    'goals' => [
        'name' => 'Goals',
        'index_title' => 'Goals List',
        'new_title' => 'New Goal',
        'create_title' => 'Create Goal',
        'edit_title' => 'Edit Goal',
        'show_title' => 'Show Goal',
        'inputs' => [
            'is_active' => 'Is Active',
            'created_by_id' => 'Created By Id',
        ],
    ],

    'goal_translations' => [
        'name' => 'Goal Translations',
        'index_title' => 'GoalTranslations List',
        'new_title' => 'New Goal translation',
        'create_title' => 'Create GoalTranslation',
        'edit_title' => 'Edit GoalTranslation',
        'show_title' => 'Show GoalTranslation',
        'inputs' => [
            'translation_id' => 'Goal',
            'name' => 'Name',
            'out_put' => 'Out Put',
            'out_come' => 'Out Come',
            'description' => 'Description',
            'locale' => 'Locale',
        ],
    ],

    'genders' => [
        'name' => 'Genders',
        'index_title' => 'Genders List',
        'new_title' => 'New Gender',
        'create_title' => 'Create Gender',
        'edit_title' => 'Edit Gender',
        'show_title' => 'Show Gender',
        'inputs' => [],
    ],

    'inititives' => [
        'name' => 'Inititives',
        'index_title' => 'Inititives List',
        'new_title' => 'New Inititive',
        'create_title' => 'Create Inititive',
        'edit_title' => 'Edit Inititive',
        'show_title' => 'Show Inititive',
        'inputs' => [
            'key_peformance_indicator_id' => 'Key Peformance Indicator',
        ],
    ],

    'key_peformance_indicators' => [
        'name' => 'Key Peformance Indicators',
        'index_title' => 'KeyPeformanceIndicators List',
        'new_title' => 'New Key peformance indicator',
        'create_title' => 'Create KeyPeformanceIndicator',
        'edit_title' => 'Edit KeyPeformanceIndicator',
        'show_title' => 'Show KeyPeformanceIndicator',
        'inputs' => [
            'weight' => 'Weight',
            'objective_id' => 'Objective',
            'strategy_id' => 'Strategy',
            'created_by_id' => 'User',
            'reporting_period_type_id' => 'Reporting Period Type',
        ],
    ],

    'objectives' => [
        'name' => 'Objectives',
        'index_title' => 'Objectives List',
        'new_title' => 'New Objective',
        'create_title' => 'Create Objective',
        'edit_title' => 'Edit Objective',
        'show_title' => 'Show Objective',
        'inputs' => [
            'goal_id' => 'Goal',
            'perspective_id' => 'Perspective',
            'created_by_id' => 'User',
            'updated_by_id' => 'User2',
            'weight' => 'Weight',
        ],
    ],

    'offices' => [
        'name' => 'Offices',
        'index_title' => 'Offices List',
        'new_title' => 'New Office',
        'create_title' => 'Create Office',
        'edit_title' => 'Edit Office',
        'show_title' => 'Show Office',
        'inputs' => [
            'holder_id' => 'User',
            'parent_office_id' => 'Office',
        ],
    ],

    'perspectives' => [
        'name' => 'Perspectives',
        'index_title' => 'Perspectives List',
        'new_title' => 'New Perspective',
        'create_title' => 'Create Perspective',
        'edit_title' => 'Edit Perspective',
        'show_title' => 'Show Perspective',
        'inputs' => [
            'created_by_id' => 'User',
            'updated_by_id' => 'User2',
        ],
    ],

    'planing_years' => [
        'name' => 'Planing Years',
        'index_title' => 'PlaningYears List',
        'new_title' => 'New Planing year',
        'create_title' => 'Create PlaningYear',
        'edit_title' => 'Edit PlaningYear',
        'show_title' => 'Show PlaningYear',
        'inputs' => [],
    ],

    'reporting_periods' => [
        'name' => 'Reporting Periods',
        'index_title' => 'ReportingPeriods List',
        'new_title' => 'New Reporting period',
        'create_title' => 'Create ReportingPeriod',
        'edit_title' => 'Edit ReportingPeriod',
        'show_title' => 'Show ReportingPeriod',
        'inputs' => [
            'planing_year_id' => 'Planing Year',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'reporting_period_type_id' => 'Reporting Period Type',
        ],
    ],

    'reporting_period_types' => [
        'name' => 'Reporting Period Types',
        'index_title' => 'ReportingPeriodTypes List',
        'new_title' => 'New Reporting period type',
        'create_title' => 'Create ReportingPeriodType',
        'edit_title' => 'Edit ReportingPeriodType',
        'show_title' => 'Show ReportingPeriodType',
        'inputs' => [],
    ],

    'strategies' => [
        'name' => 'Strategies',
        'index_title' => 'Strategies List',
        'new_title' => 'New Strategy',
        'create_title' => 'Create Strategy',
        'edit_title' => 'Edit Strategy',
        'show_title' => 'Show Strategy',
        'inputs' => [
            'objective_id' => 'Objective',
            'created_by_id' => 'User',
            'updated_by_id' => 'User2',
        ],
    ],

    'suitable_kpis' => [
        'name' => 'Suitable Kpis',
        'index_title' => 'SuitableKpis List',
        'new_title' => 'New Suitable kpi',
        'create_title' => 'Create SuitableKpi',
        'edit_title' => 'Edit SuitableKpi',
        'show_title' => 'Show SuitableKpi',
        'inputs' => [
            'key_peformance_indicator_id' => 'Key Peformance Indicator',
            'office_id' => 'Office',
            'planing_year_id' => 'Planing Year',
        ],
    ],

    'gender_translations' => [
        'name' => 'Gender Translations',
        'index_title' => 'GenderTranslations List',
        'new_title' => 'New Gender translation',
        'create_title' => 'Create GenderTranslation',
        'edit_title' => 'Edit GenderTranslation',
        'show_title' => 'Show GenderTranslation',
        'inputs' => [
            'name' => 'Name',
            'description' => 'Description',
            'gender_id' => 'Gender',
        ],
    ],

    'inititive_translations' => [
        'name' => 'Inititive Translations',
        'index_title' => 'InititiveTranslations List',
        'new_title' => 'New Inititive translation',
        'create_title' => 'Create InititiveTranslation',
        'edit_title' => 'Edit InititiveTranslation',
        'show_title' => 'Show InititiveTranslation',
        'inputs' => [
            'inititive_id' => 'Inititive',
            'name' => 'Name',
            'description' => 'Description',
        ],
    ],

    'key_peformance_indicator_translations' => [
        'name' => 'Key Peformance Indicator Translations',
        'index_title' => 'KeyPeformanceIndicatorTranslations List',
        'new_title' => 'New Key peformance indicator translation',
        'create_title' => 'Create KeyPeformanceIndicatorTranslation',
        'edit_title' => 'Edit KeyPeformanceIndicatorTranslation',
        'show_title' => 'Show KeyPeformanceIndicatorTranslation',
        'inputs' => [
            'name' => 'Name',
            'description' => 'Description',
            'out_put' => 'Out Put',
            'out_come' => 'Out Come',
            'translation_id' => 'Key Peformance Indicator',
        ],
    ],

    'objective_translations' => [
        'name' => 'Objective Translations',
        'index_title' => 'ObjectiveTranslations List',
        'new_title' => 'New Objective translation',
        'create_title' => 'Create ObjectiveTranslation',
        'edit_title' => 'Edit ObjectiveTranslation',
        'show_title' => 'Show ObjectiveTranslation',
        'inputs' => [
            'name' => 'Name',
            'description' => 'Description',
            'out_put' => 'Out Put',
            'out_come' => 'Out Come',
            'translation_id' => 'Objective',
        ],
    ],

    'office_translations' => [
        'name' => 'Office Translations',
        'index_title' => 'OfficeTranslations List',
        'new_title' => 'New Office translation',
        'create_title' => 'Create OfficeTranslation',
        'edit_title' => 'Edit OfficeTranslation',
        'show_title' => 'Show OfficeTranslation',
        'inputs' => [
            'translation_id' => 'Parent Office',
            'name' => 'Name',
            'description' => 'Description',
        ],
    ],

    'perspective_translations' => [
        'name' => 'Perspective Translations',
        'index_title' => 'PerspectiveTranslations List',
        'new_title' => 'New Perspective translation',
        'create_title' => 'Create PerspectiveTranslation',
        'edit_title' => 'Edit PerspectiveTranslation',
        'show_title' => 'Show PerspectiveTranslation',
        'inputs' => [
            'name' => 'Name',
            'description' => 'Description',
            'translation_id' => 'Perspective',
        ],
    ],

    'plan_accomplishments' => [
        'name' => 'Plan Accomplishments',
        'index_title' => 'PlanAccomplishments List',
        'new_title' => 'New Plan accomplishment',
        'create_title' => 'Create PlanAccomplishment',
        'edit_title' => 'Edit PlanAccomplishment',
        'show_title' => 'Show PlanAccomplishment',
        'inputs' => [
            'kpi' => 'KPI Name',
            'reporting_period_id' => 'Reporting Period',
            'plan_value' => 'Plan Value',
            'accom_value' => 'Accom Value',
            'plan_status' => 'Plan Status',
            'accom_status' => 'Accom Status',
        ],
    ],

    'planing_year_translations' => [
        'name' => 'Planing Year Translations',
        'index_title' => 'PlaningYearTranslations List',
        'new_title' => 'New Planing year translation',
        'create_title' => 'Create PlaningYearTranslation',
        'edit_title' => 'Edit PlaningYearTranslation',
        'show_title' => 'Show PlaningYearTranslation',
        'inputs' => [
            'planing_year_id' => 'Planing Year',
            'name' => 'Name',
            'description' => 'Description',
        ],
    ],

    'reporting_period_translations' => [
        'name' => 'Reporting Period Translations',
        'index_title' => 'ReportingPeriodTranslations List',
        'new_title' => 'New Reporting period translation',
        'create_title' => 'Create ReportingPeriodTranslation',
        'edit_title' => 'Edit ReportingPeriodTranslation',
        'show_title' => 'Show ReportingPeriodTranslation',
        'inputs' => [
            'name' => 'Name',
            'description' => 'Description',
            'reporting_period_id' => 'Reporting Period',
        ],
    ],

    'reporting_period_type_translations' => [
        'name' => 'Reporting Period Type Translations',
        'index_title' => 'ReportingPeriodTypeTranslations List',
        'new_title' => 'New Reporting period type translation',
        'create_title' => 'Create ReportingPeriodTypeTranslation',
        'edit_title' => 'Edit ReportingPeriodTypeTranslation',
        'show_title' => 'Show ReportingPeriodTypeTranslation',
        'inputs' => [
            'reporting_period_type_id' => 'Reporting Period Type',
            'name' => 'Name',
            'description' => 'Description',
        ],
    ],

    'strategy_translations' => [
        'name' => 'Strategy Translations',
        'index_title' => 'StrategyTranslations List',
        'new_title' => 'New Strategy translation',
        'create_title' => 'Create StrategyTranslation',
        'edit_title' => 'Edit StrategyTranslation',
        'show_title' => 'Show StrategyTranslation',
        'inputs' => [
            'name' => 'Name',
            'discription' => 'Discription',
            'translation_id' => 'Strategy',
        ],
    ],

    'languages' => [
        'name' => 'Languages',
        'index_title' => 'Languages List',
        'new_title' => 'New Language',
        'create_title' => 'Create Language',
        'edit_title' => 'Edit Language',
        'show_title' => 'Show Language',
        'inputs' => [
            'name' => 'Name',
            'description' => 'Description',
            'locale' => 'Locale',
            'created_by_id' => 'User',
        ],
    ],

    'roles' => [
        'name' => 'Roles',
        'index_title' => 'Roles List',
        'create_title' => 'Create Role',
        'edit_title' => 'Edit Role',
        'show_title' => 'Show Role',
        'inputs' => [
            'name' => 'Name',
        ],
    ],

    'permissions' => [
        'name' => 'Permissions',
        'index_title' => 'Permissions List',
        'create_title' => 'Create Permission',
        'edit_title' => 'Edit Permission',
        'show_title' => 'Show Permission',
        'inputs' => [
            'name' => 'Name',
        ],
    ],
];
