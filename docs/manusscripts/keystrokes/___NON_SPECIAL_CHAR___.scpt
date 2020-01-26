on run argv
    tell application "System Events"
        set textToType to item 1 of argv
        keystroke textToType
    end tell
end run