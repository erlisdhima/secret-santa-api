
## 1. Architecture Decisions

- **Domain Separation**: Logic is split into `Entity`, `Repository`, `Service`, `Controller`, and `Dto` layers.
- **Service Layer**: Business logic is encapsulated in service classes like `GiftAssignmentService`.
- **DTO Usage**: Input data is validated and handled using Data Transfer Objects.
- **Factory Use**: Object creation is managed by factories, improving separation of concerns.
- **Enums**: Use of enums (`EventStatusEnum`, `PreferenceTypeEnum`) enhances code readability and safety.

## 2. Effective Data Structure Usage

- **Doctrine Collections**: Properly used for entity relationships.
- **Map/Filter**: Efficient use for transforming collections during logic execution.
- **Testing IDs**: Reflection used to inject IDs in tests (acceptable in test contexts).

## 3. Possible future improvements
- **Controller**
  - Instead of using the traditional Symfony controllers, use something like API Platform which can also generate API docs automatically.
- GraphQl instead of REST?
  - for the scale of this application, REST is good enough. However, if implementing more features like the chat, it might be a good idea to consider. 
- **Test Fixtures**
  - Better implementation of data fixtures. Currently, it doesn't cover all scenarios.
- **Logging**
  - Add logging for debugging and visibility.

## 4. Issues & to complete

- **Code Style**
  - phpstan and phpcs has been installed, however not all issues have been fixed.
- **Naming** 
  - All class names (and possibly in other areas) need to be reviewed to have a more consistent naming convention
- **Error checks**
  - Not all errors are handled. In some cases 'callers' (i.e. controllers) need to handle exceptions and show a friendly message.
- **Tests**
  - Only one unit test was created. More unit tests are needed. No API testing is in place.
- **Validation**:
    - Validation needs to be re-checked. Not everything has been tested accordingly. (e.g. check if provided preferences in include are not the same as in exclude)
